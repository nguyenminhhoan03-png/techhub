<?php

declare(strict_types=1);

namespace Infrastructure\Tool\Providers;

use Application\Bus\Contracts\CommandBusContract;
use Application\Bus\Contracts\QueryBusContract;
use Application\Tool\CommandHandlers\ExecuteToolCommandHandler;
use Application\Tool\Commands\ExecuteToolCommand;
use Application\Tool\Contracts\ToolServiceContract;
use Application\Tool\Queries\GetToolBySlugQuery;
use Application\Tool\Queries\GetToolBySlugQueryHandler;
use Application\Tool\Queries\GetToolCategoriesQuery;
use Application\Tool\Queries\GetToolCategoriesQueryHandler;
use Application\Tool\Queries\GetToolsQuery;
use Application\Tool\Queries\GetToolsQueryHandler;
use Application\Tool\Services\ToolService;
use Domain\Tool\Contracts\ToolRegistryContract;
use Domain\Tool\Repositories\ToolRepositoryContract;
use Domain\Tool\Tools\Calculators\BmiCalculatorTool;
use Domain\Tool\Tools\Calculators\LoanCalculatorTool;
use Domain\Tool\Tools\Calculators\PercentageCalculatorTool;
use Domain\Tool\Tools\Developer\Base64Tool;
use Domain\Tool\Tools\Developer\HashGeneratorTool;
use Domain\Tool\Tools\Developer\JsonFormatterTool;
use Domain\Tool\Tools\Developer\JwtDebuggerTool;
use Domain\Tool\Tools\Developer\RegexTesterTool;
use Domain\Tool\Tools\Developer\UrlEncoderDecoderTool;
use Domain\Tool\Tools\Image\ImageColorExtractorTool;
use Domain\Tool\Tools\Image\ImageMetadataTool;
use Domain\Tool\Tools\Seo\MetaTagGeneratorTool;
use Domain\Tool\Tools\Seo\OpenGraphGeneratorTool;
use Domain\Tool\Tools\Seo\RobotsTxtGeneratorTool;
use Domain\Tool\Tools\Seo\SchemaGeneratorTool;
use Domain\Tool\Tools\Seo\SerpPreviewTool;
use Domain\Tool\Tools\Seo\SitemapGeneratorTool;
use Domain\Tool\Tools\Seo\SlugGeneratorTool;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Tool\Persistence\Repositories\ToolRepository;
use Infrastructure\Tool\Registry\ToolRegistry;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public array $singletons = [
        ToolRepositoryContract::class => ToolRepository::class,
        ToolServiceContract::class => ToolService::class,
    ];

    public function register(): void
    {
        $this->app->singleton(ToolRegistryContract::class, function (): ToolRegistryContract {
            $registry = new ToolRegistry();

            // Register Developer Tools
            $registry->register(new JsonFormatterTool());
            $registry->register(new Base64Tool());
            $registry->register(new HashGeneratorTool());
            $registry->register(new JwtDebuggerTool());
            $registry->register(new RegexTesterTool());
            $registry->register(new UrlEncoderDecoderTool());

            // Register Calculator Tools
            $registry->register(new LoanCalculatorTool());
            $registry->register(new PercentageCalculatorTool());
            $registry->register(new BmiCalculatorTool());

            // Register Image Tools
            $registry->register(new ImageMetadataTool());
            $registry->register(new ImageColorExtractorTool());

            // Register SEO Tools
            $registry->register(new SerpPreviewTool());
            $registry->register(new MetaTagGeneratorTool());
            $registry->register(new SchemaGeneratorTool());
            $registry->register(new OpenGraphGeneratorTool());
            $registry->register(new RobotsTxtGeneratorTool());
            $registry->register(new SitemapGeneratorTool());
            $registry->register(new SlugGeneratorTool());

            return $registry;
        });
    }

    public function boot(): void
    {
        $this->registerCommandHandlers();
        $this->registerQueryHandlers();
    }

    private function registerCommandHandlers(): void
    {
        /** @var CommandBusContract $commandBus */
        $commandBus = $this->app->make(CommandBusContract::class);
        $commandBus->register([
            ExecuteToolCommand::class => ExecuteToolCommandHandler::class,
        ]);
    }

    private function registerQueryHandlers(): void
    {
        /** @var QueryBusContract $queryBus */
        $queryBus = $this->app->make(QueryBusContract::class);
        $queryBus->register([
            GetToolsQuery::class => GetToolsQueryHandler::class,
            GetToolBySlugQuery::class => GetToolBySlugQueryHandler::class,
            GetToolCategoriesQuery::class => GetToolCategoriesQueryHandler::class,
        ]);
    }
}
