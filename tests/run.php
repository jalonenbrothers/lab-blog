<?php

declare(strict_types=1);

/** Tiny test runner (same shape as the tracker's). */

final class T
{
    public function __construct(private int $passed = 0, private int $failed = 0)
    {
    }

    public function assertSame(mixed $expected, mixed $actual, string $label = ''): void
    {
        if ($expected === $actual) {
            $this->passed++;
            return;
        }

        $this->failed++;
        $where = $label !== '' ? " ($label)" : '';
        fprintf(STDERR, "FAIL%s\n  expected: %s\n  actual:   %s\n", $where, var_export($expected, true), var_export($actual, true));
    }

    public function assertMatches(string $pattern, string $actual, string $label = ''): void
    {
        if (preg_match($pattern, $actual) === 1) {
            $this->passed++;
            return;
        }

        $this->failed++;
        fprintf(STDERR, "FAIL (%s)\n  pattern: %s\n  actual:   %s\n", $label, $pattern, $actual);
    }

    public function report(): int
    {
        printf("%d passed, %d failed\n", $this->passed, $this->failed);

        return $this->failed === 0 ? 0 : 1;
    }
}

foreach (glob(__DIR__ . '/test_*.php') as $file) {
    require_once $file;
}

$t = new T();
foreach (get_defined_functions()['user'] as $fn) {
    if (str_starts_with($fn, 'test_')) {
        $fn($t);
    }
}

exit($t->report());