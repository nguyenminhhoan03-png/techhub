<?php

declare(strict_types=1);

namespace Domain\Tool\Enums;

enum ToolEngineType: string
{
    case ClientBrowser = 'client_browser';
    case ServerSync = 'server_sync';
    case ServerAsyncQueue = 'server_async_queue';
    case AiApi = 'ai_api';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
