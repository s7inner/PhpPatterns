<?php

declare(strict_types=1);

namespace DesignPatterns\Structural\Adapter\DTO;

class PaymentResultDTO
{
    public function __construct(
        private bool $success,
        private ?string $transactionId
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }
}
