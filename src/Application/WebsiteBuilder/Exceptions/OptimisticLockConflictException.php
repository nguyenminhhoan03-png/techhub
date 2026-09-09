<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Exceptions;

use Exception;

class OptimisticLockConflictException extends Exception
{
    public function __construct(
        public readonly int $currentVersionNumber,
        public readonly mixed $latestContent,
        string $message = 'Trang đã được cập nhật từ một phiên chỉnh sửa khác (Xung đột phiên bản).',
    ) {
        parent::__construct($message, 409);
    }
}
