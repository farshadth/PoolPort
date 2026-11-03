<?php

namespace PoolPort\Tests;

use PoolPort\Jalali;
use PHPUnit\Framework\TestCase;

class JalaliTest extends TestCase
{
    public function testFromGregorian()
    {
        $this->assertSame([1403, 1, 1], Jalali::fromGregorian(2024, 3, 20));
        $this->assertSame([1402, 12, 29], Jalali::fromGregorian(2024, 3, 19));
        $this->assertSame([1405, 7, 30], Jalali::fromGregorian(2026, 10, 22));
        $this->assertSame([1399, 12, 30], Jalali::fromGregorian(2021, 3, 20));
    }

    public function testDateFormat()
    {
        $timestamp = mktime(13, 5, 9, 10, 22, 2026);

        $this->assertSame('1405/07/30 13:05:09', Jalali::date('Y/m/d H:i:s', $timestamp));
    }
}
