<?php

declare(strict_types=1);

namespace Application\SEO\Services;

use Google\Client as GoogleClient;
use Google\Service\Exception as GoogleServiceException;
use Google\Service\Indexing as GoogleIndexingApi;
use Google\Service\Indexing\UrlNotification;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    private ?GoogleClient $client = null;

    public function __construct()
    {
        $keyPath = config('services.google.indexing_key_path');
        
        if ($keyPath && is_string($keyPath) && file_exists($keyPath)) {
            $this->client = new GoogleClient();
            $this->client->setAuthConfig($keyPath);
            $this->client->addScope(GoogleIndexingApi::INDEXING);
            $this->client->setUseBatch(true);
        }
    }

    /**
     * @return bool True if Google Client is initialized (JSON key exists)
     */
    public function isReady(): bool
    {
        return $this->client !== null;
    }

    /**
     * Push a list of URLs to Google Indexing API.
     * 
     * @param array<string> $urls Array of absolute URLs to push.
     * @param string $type 'URL_UPDATED' or 'URL_DELETED'
     * @return array{success: int, failed: int, errors: array<string>}
     */
    public function pushUrls(array $urls, string $type = 'URL_UPDATED'): array
    {
        if ( ! $this->client) {
            Log::warning('Google Indexing API: Client not initialized (JSON key missing).');
            return ['success' => 0, 'failed' => count($urls), 'errors' => ['JSON Key is missing']];
        }

        $service = new GoogleIndexingApi($this->client);
        $batch = $service->createBatch();
        
        // Google Indexing API batch limit is 100 requests per batch.
        // If there are more than 100 URLs, we chunk them.
        $chunks = array_chunk($urls, 100);
        $totalSuccess = 0;
        $totalFailed = 0;
        $errors = [];

        foreach ($chunks as $chunk) {
            foreach ($chunk as $url) {
                $notification = new UrlNotification();
                $notification->setUrl($url);
                $notification->setType($type);
                $batch->add($service->urlNotifications->publish($notification));
            }

            try {
                $results = $batch->execute();
                
                foreach ($results as $result) {
                    if ($result instanceof GoogleServiceException || $result instanceof \Exception) {
                        $totalFailed++;
                        $errors[] = $result->getMessage();
                    } else {
                        $totalSuccess++;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Google Indexing API Batch Error: " . $e->getMessage());
                $totalFailed += count($chunk);
                $errors[] = $e->getMessage();
            }
        }

        return [
            'success' => $totalSuccess,
            'failed' => $totalFailed,
            'errors' => $errors,
        ];
    }
}
