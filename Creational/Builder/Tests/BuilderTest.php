<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Builder\Tests;

use DateTimeImmutable;
use DesignPatterns\Creational\Builder\ReportBuilder;
use DesignPatterns\Creational\Builder\ReportDirector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function testCanBuildReportWithFluentBuilder(): void
    {
        $report = (new ReportBuilder())
            ->title('Monthly')
            ->format('pdf')
            ->locale('uk')
            ->includeCharts()
            ->metrics(['sales', 'profit', 'sales'])
            ->period(
                new DateTimeImmutable('2026-03-01'),
                new DateTimeImmutable('2026-03-31')
            )
            ->includeExecutiveSummary()
            ->build();

        $this->assertSame('Monthly', $report->getTitle());
        $this->assertSame('pdf', $report->getFormat());
        $this->assertSame('uk', $report->getLocale());
        $this->assertTrue($report->includeCharts());
        $this->assertSame(['sales', 'profit'], $report->getMetrics());
        $this->assertTrue($report->includeExecutiveSummary());
        $this->assertSame('2026-03-01', $report->getPeriodStart()?->format('Y-m-d'));
        $this->assertSame('2026-03-31', $report->getPeriodEnd()?->format('Y-m-d'));
    }

    public function testDirectorBuildsPresetReport(): void
    {
        $director = new ReportDirector(new ReportBuilder());

        $report = $director->buildMonthlyPerformanceReport(
            new DateTimeImmutable('2026-03-01'),
            new DateTimeImmutable('2026-03-31')
        );

        $this->assertSame('Monthly Performance', $report->getTitle());
        $this->assertSame('pdf', $report->getFormat());
        $this->assertTrue($report->includeCharts());
        $this->assertTrue($report->includeExecutiveSummary());
        $this->assertSame(['sales', 'profit', 'conversion'], $report->getMetrics());
    }

    public function testThrowsExceptionForInvalidPeriod(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Report period end date must be greater than or equal to start date.');

        (new ReportBuilder())->period(
            new DateTimeImmutable('2026-03-31'),
            new DateTimeImmutable('2026-03-01')
        );
    }

    public function testThrowsExceptionForEmptyTitle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Report title must not be empty.');

        (new ReportBuilder())
            ->title('   ')
            ->build();
    }
}
