<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Builder;

use DateTimeInterface;
use DesignPatterns\Creational\Builder\Interfaces\ReportBuilder as ReportBuilderContract;
use InvalidArgumentException;

class ReportBuilder implements ReportBuilderContract
{
    private string $title = 'Untitled';
    private bool $includeCharts = false;
    private bool $includeRawData = false;
    private string $format = 'pdf';
    private string $locale = 'en';
    /** @var array<string> */
    private array $metrics = [];
    private ?DateTimeInterface $periodStart = null;
    private ?DateTimeInterface $periodEnd = null;
    private bool $includeExecutiveSummary = false;

    public function title(string $title): ReportBuilderContract
    {
        $this->title = trim($title);

        return $this;
    }

    public function format(string $format): ReportBuilderContract
    {
        $this->format = strtolower(trim($format));

        return $this;
    }

    public function locale(string $locale): ReportBuilderContract
    {
        $this->locale = strtolower(trim($locale));

        return $this;
    }

    public function includeCharts(): ReportBuilderContract
    {
        $this->includeCharts = true;

        return $this;
    }

    public function includeRawData(): ReportBuilderContract
    {
        $this->includeRawData = true;

        return $this;
    }

    /**
     * @param array<string> $metrics
     */
    public function metrics(array $metrics): ReportBuilderContract
    {
        $this->metrics = array_values(array_unique($metrics));

        return $this;
    }

    public function period(DateTimeInterface $start, DateTimeInterface $end): ReportBuilderContract
    {
        if ($end < $start) {
            throw new InvalidArgumentException('Report period end date must be greater than or equal to start date.');
        }

        $this->periodStart = $start;
        $this->periodEnd = $end;

        return $this;
    }

    public function includeExecutiveSummary(): ReportBuilderContract
    {
        $this->includeExecutiveSummary = true;

        return $this;
    }

    public function build(): Report
    {
        if ($this->title === '') {
            throw new InvalidArgumentException('Report title must not be empty.');
        }

        if ($this->format === '') {
            throw new InvalidArgumentException('Report format must not be empty.');
        }

        if ($this->locale === '') {
            throw new InvalidArgumentException('Report locale must not be empty.');
        }

        $report = new Report(
            $this->title,
            $this->includeCharts,
            $this->includeRawData,
            $this->format,
            $this->locale,
            $this->metrics,
            $this->periodStart,
            $this->periodEnd,
            $this->includeExecutiveSummary
        );

        $this->reset();

        return $report;
    }

    private function reset(): void
    {
        $this->title = 'Untitled';
        $this->includeCharts = false;
        $this->includeRawData = false;
        $this->format = 'pdf';
        $this->locale = 'en';
        $this->metrics = [];
        $this->periodStart = null;
        $this->periodEnd = null;
        $this->includeExecutiveSummary = false;
    }
}
