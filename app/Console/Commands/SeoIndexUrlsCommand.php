<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Application\SEO\Services\GoogleIndexingService;
use Domain\Tool\Entities\Tool;
use Domain\Article\Entities\Article;
use Domain\Game\Entities\Game;
use Illuminate\Console\Command;

class SeoIndexUrlsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:push-index {--test : Push a test URL instead of DB records} {--limit=100 : Max URLs to push}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pushes latest URLs to Google Indexing API for rapid indexing';

    /**
     * Execute the console command.
     */
    public function handle(GoogleIndexingService $indexingService): int
    {
        if ( ! $indexingService->isReady()) {
            $this->error("❌ Google Indexing API is not configured!");
            $this->line("Please download the Service Account JSON key from Google Cloud Platform.");
            $this->line("Rename it to 'google-service-account.json' and place it in 'storage/app/'.");
            return Command::FAILURE;
        }

        if ($this->option('test')) {
            $this->info("🧪 Running in TEST mode. Pushing home page URL...");
            $urls = [url('/')];
            $this->push($indexingService, $urls);
            return Command::SUCCESS;
        }

        $limit = (int) $this->option('limit');
        $this->info("🚀 Starting to push latest {$limit} URLs to Google Indexing API...");

        $urls = [];
        $urls[] = url('/');
        $urls[] = url('/tools');
        $urls[] = url('/articles');
        $urls[] = url('/games');

        // Fetch Tools
        if (class_exists(Tool::class)) {
            $tools = Tool::where('is_active', true)->orderBy('updated_at', 'desc')->limit($limit)->get();
            foreach ($tools as $tool) {
                $urls[] = url('/tools/' . $tool->slug);
            }
        }

        // Fetch Articles
        if (class_exists(Article::class)) {
            $articles = Article::where('status', 'published')->orderBy('updated_at', 'desc')->limit($limit)->get();
            foreach ($articles as $article) {
                $urls[] = url('/articles/' . $article->slug);
            }
        }

        // Fetch Games
        if (class_exists(Game::class)) {
            $games = Game::where('is_active', true)->orderBy('updated_at', 'desc')->limit($limit)->get();
            foreach ($games as $game) {
                $urls[] = url('/games/' . $game->slug);
            }
        }

        // Limit total if needed
        $urls = array_slice(array_unique($urls), 0, $limit);

        $this->line("Found " . count($urls) . " URLs to process.");
        
        $this->push($indexingService, $urls);

        return Command::SUCCESS;
    }

    private function push(GoogleIndexingService $indexingService, array $urls): void
    {
        $this->info("📡 Pushing to Google...");
        $result = $indexingService->pushUrls($urls);

        if ($result['success'] > 0) {
            $this->info("✅ Successfully pushed {$result['success']} URLs!");
        }

        if ($result['failed'] > 0) {
            $this->error("❌ Failed to push {$result['failed']} URLs.");
            foreach ($result['errors'] as $error) {
                $this->error("- " . $error);
            }
        }
    }
}
