<?php

namespace PoolPort;

class Jalali
{
    /**
     * Format the current time as a Jalali (Solar Hijri) date.
     * Supported Jalali tokens: Y, y, m, n, d, j. Other tokens are passed to date().
     *
     * @param string $format
     * @param int|null $timestamp
     *
     * @return string
     */
    public static function date($format, $timestamp = null)
    {
        $timestamp = $timestamp === null ? time() : (int) $timestamp;

        if (function_exists('jdate')) {
            return (string) jdate($format, $timestamp);
        }

        list($jy, $jm, $jd) = self::fromGregorian(
            (int) date('Y', $timestamp),
            (int) date('n', $timestamp),
            (int) date('j', $timestamp)
        );

        $tokens = [
            'Y' => (string) $jy,
            'y' => substr((string) $jy, -2),
            'm' => sprintf('%02d', $jm),
            'n' => (string) $jm,
            'd' => sprintf('%02d', $jd),
            'j' => (string) $jd,
        ];

        $result = '';
        $length = strlen($format);

        for ($i = 0; $i < $length; $i++) {
            $char = $format[$i];

            if ($char === '\\' && $i + 1 < $length) {
                $result .= $format[++$i];
            } elseif (isset($tokens[$char])) {
                $result .= $tokens[$char];
            } else {
                $result .= date($char, $timestamp);
            }
        }

        return $result;
    }

    /**
     * @param int $gy
     * @param int $gm
     * @param int $gd
     *
     * @return array [year, month, day]
     */
    public static function fromGregorian($gy, $gm, $gd)
    {
        $gDaysInMonth = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = $gm > 2 ? $gy + 1 : $gy;

        $days = 355666 + (365 * $gy) + (int) (($gy2 + 3) / 4) - (int) (($gy2 + 99) / 100)
            + (int) (($gy2 + 399) / 400) + $gd + $gDaysInMonth[$gm - 1];

        $jy = -1595 + (33 * (int) ($days / 12053));
        $days %= 12053;
        $jy += 4 * (int) ($days / 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + (int) ($days / 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + (int) (($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }
}
