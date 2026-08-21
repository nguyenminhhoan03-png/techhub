<?php

declare(strict_types=1);

namespace Domain\Tool\ValueObjects;

final readonly class ToolResult
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed>|null $meta
     */
    public function __construct(
        public bool $isSuccess,
        public array $data = [],
        public ?string $outputFilePath = null,
        public ?string $mimeType = null,
        public ?int $outputSizeBytes = null,
        public ?int $executionTimeMs = null,
        public ?string $errorMessage = null,
        public ?array $meta = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed>|null $meta
     */
    public static function success(
        array $data = [],
        ?string $outputFilePath = null,
        ?string $mimeType = null,
        ?int $outputSizeBytes = null,
        ?int $executionTimeMs = null,
        ?array $meta = null,
    ): self {
        return new self(
            isSuccess: true,
            data: $data,
            outputFilePath: $outputFilePath,
            mimeType: $mimeType,
            outputSizeBytes: $outputSizeBytes,
            executionTimeMs: $executionTimeMs,
            errorMessage: null,
            meta: $meta,
        );
    }

    public static function failure(string $errorMessage, ?int $executionTimeMs = null): self
    {
        return new self(
            isSuccess: false,
            data: [],
            outputFilePath: null,
            mimeType: null,
            outputSizeBytes: null,
            executionTimeMs: $executionTimeMs,
            errorMessage: $errorMessage,
            meta: null,
        );
    }
}
