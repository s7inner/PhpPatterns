<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Builder\Interfaces;

use DateTimeInterface;
use DesignPatterns\Creational\Builder\Report;

interface ReportBuilder
{
    public function title(string $title): self;

    public function format(string $format): self;

    public function locale(string $locale): self;

    public function includeCharts(): self;

    public function includeRawData(): self;

    /**
     * @param array<string> $metrics
     */
    public function metrics(array $metrics): self;

    public function period(DateTimeInterface $start, DateTimeInterface $end): self;

    public function includeExecutiveSummary(): self;

    public function build(): Report;
}
