<?php

declare(strict_types=1);

namespace Application\Article\Commands;

use Application\Ai\Services\LlmJsonSanitizer;
use Domain\Article\Entities\Article;
use Illuminate\Console\Command;

class RepairArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:repair-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan and repair any articles corrupted with raw JSON or ```json titles';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Scanning for corrupted articles...');

        $articles = Article::all();
        $healed = 0;

        foreach ($articles as $article) {
            $wasHealed = LlmJsonSanitizer::cleanCorruptedArticle($article);
            if ($wasHealed) {
                $this->line("<info>Healed article ID {$article->id}:</info> {$article->title} (Slug: {$article->slug})");
                $healed++;
            }
        }

        $this->info("Completed. Successfully repaired {$healed} article(s).");

        return Command::SUCCESS;
    }
}
