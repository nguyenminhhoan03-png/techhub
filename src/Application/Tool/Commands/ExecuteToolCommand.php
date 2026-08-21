<?php

declare(strict_types=1);

namespace Application\Tool\Commands;

use Application\Bus\Command;

final class ExecuteToolCommand extends Command
{
    /**
     * @param array<string, mixed> $input
     */
    public function __construct(
        public string $toolSlug,
        public array $input,
        public ?int $userId = null,
        public string $ipAddress = '127.0.0.1',
    ) {}
}
