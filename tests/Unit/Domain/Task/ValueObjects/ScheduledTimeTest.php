<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Task\ValueObjects;

use App\Domain\Task\ValueObjects\ScheduledTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ScheduledTimeTest extends TestCase
{
    public function test_creates_with_valid_time(): void
    {
        $time = new ScheduledTime('08:30');

        $this->assertSame('08:30', $time->value);
    }

    public function test_formatted_returns_hhmm(): void
    {
        $time = new ScheduledTime('08:30:00');

        $this->assertSame('08:30', $time->formatted());
    }

    public function test_throws_when_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ScheduledTime('8:30');
    }

    public function test_throws_when_invalid_hour(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ScheduledTime('25:00');
    }

    public function test_throws_when_invalid_minute(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ScheduledTime('08:60');
    }

    public function test_casts_to_string(): void
    {
        $time = new ScheduledTime('14:00');

        $this->assertSame('14:00', (string) $time);
    }
}
