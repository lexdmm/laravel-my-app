<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Task\ValueObjects;

use App\Domain\Task\ValueObjects\TaskTitle;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TaskTitleTest extends TestCase
{
    public function test_creates_with_valid_title(): void
    {
        $title = new TaskTitle('Reunião com cliente');

        $this->assertSame('Reunião com cliente', $title->value);
    }

    public function test_throws_when_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TaskTitle('');
    }

    public function test_throws_when_only_whitespace(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TaskTitle('   ');
    }

    public function test_throws_when_exceeds_255_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new TaskTitle(str_repeat('a', 256));
    }

    public function test_accepts_exactly_255_characters(): void
    {
        $title = new TaskTitle(str_repeat('a', 255));

        $this->assertSame(255, mb_strlen($title->value));
    }

    public function test_casts_to_string(): void
    {
        $title = new TaskTitle('Minha tarefa');

        $this->assertSame('Minha tarefa', (string) $title);
    }
}
