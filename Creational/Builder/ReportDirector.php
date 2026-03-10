<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Builder;

use DateTimeInterface;
use DesignPatterns\Creational\Builder\Interfaces\ReportBuilder;

class ReportDirector
{
    public function __construct(
        private ReportBuilder $builder
    ) {
    }

    public function buildMonthlyPerformanceReport(DateTimeInterface $start, DateTimeInterface $end): Report
    {
        return $this->builder
            ->title('Monthly Performance')
            ->format('pdf')
            ->locale('en')
            ->metrics(['sales', 'profit', 'conversion'])
            ->includeCharts()
            ->includeExecutiveSummary()
            ->period($start, $end)
            ->build();
    }

    public function buildRawDataExport(DateTimeInterface $start, DateTimeInterface $end): Report
    {
        return $this->builder
            ->title('Raw Data Export')
            ->format('csv')
            ->locale('en')
            ->metrics(['transactions', 'refunds'])
            ->includeRawData()
            ->period($start, $end)
            ->build();
    }
}
