<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Builder;

use DateTimeInterface;

class Report
{
    /**
     * @param array<string> $metrics
     */
    public function __construct(
        private string $title,
        private bool $includeCharts,
        private bool $includeRawData,
        private string $format,
        private string $locale,
        private array $metrics,
        private ?DateTimeInterface $periodStart,
        private ?DateTimeInterface $periodEnd,
        private bool $includeExecutiveSummary
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function includeCharts(): bool
    {
        return $this->includeCharts;
    }

    public function includeRawData(): bool
    {
        return $this->includeRawData;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return array<string>
     */
    public function getMetrics(): array
    {
        return $this->metrics;
    }

    public function getPeriodStart(): ?DateTimeInterface
    {
        return $this->periodStart;
    }

    public function getPeriodEnd(): ?DateTimeInterface
    {
        return $this->periodEnd;
    }

    public function includeExecutiveSummary(): bool
    {
        return $this->includeExecutiveSummary;
    }
}
