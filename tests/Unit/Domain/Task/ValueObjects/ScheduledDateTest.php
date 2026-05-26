<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Task\ValueObjects;

use App\Domain\Task\ValueObjects\ScheduledDate;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ScheduledDateTest extends TestCase
{
    public function test_creates_with_valid_date(): void
    {
        $date = new ScheduledDate('2026-05-25');

        $this->assertSame('2026-05-25', $date->toDateString());
    }

    public function test_formats_date(): void
    {
        $date = new ScheduledDate('2026-05-25');

        $this->assertSame('25/05/2026', $date->format('d/m/Y'));
    }

    public function test_throws_when_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ScheduledDate('25-05-2026');
    }

    public function test_throws_when_not_a_date(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ScheduledDate('amanhã');
    }

    public function test_casts_to_string(): void
    {
        $date = new ScheduledDate('2026-05-25');

        $this->assertSame('2026-05-25', (string) $date);
    }
}
