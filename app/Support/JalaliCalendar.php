<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Small dependency-free Jalali calendar helper for Persian UI labels.
 *
 * Database timestamps stay Gregorian; only calendar boundaries and labels
 * shown to users use the Jalali calendar.
 */
final class JalaliCalendar
{
    public const MONTHS = [
        'فروردین',
        'اردیبهشت',
        'خرداد',
        'تیر',
        'مرداد',
        'شهریور',
        'مهر',
        'آبان',
        'آذر',
        'دی',
        'بهمن',
        'اسفند',
    ];

    private const WEEKDAYS = [
        'یکشنبه',
        'دوشنبه',
        'سه‌شنبه',
        'چهارشنبه',
        'پنجشنبه',
        'جمعه',
        'شنبه',
    ];

    public static function fromGregorian(CarbonInterface $date): array
    {
        $gy = (int) $date->year;
        $gm = (int) $date->month;
        $gd = (int) $date->day;
        $monthDays = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        if ($gy > 1600) {
            $jy = 979;
            $gy -= 1600;
        } else {
            $jy = 0;
            $gy -= 621;
        }

        $gy2 = $gm > 2 ? $gy + 1 : $gy;
        $days = (365 * $gy)
            + intdiv($gy2 + 3, 4)
            - intdiv($gy2 + 99, 100)
            + intdiv($gy2 + 399, 400)
            - 80
            + $gd
            + $monthDays[$gm - 1];

        $jy += 33 * intdiv($days, 12053);
        $days %= 12053;
        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }

    public static function toGregorian(int $jy, int $jm, int $jd = 1): Carbon
    {
        if ($jm < 1 || $jm > 12 || $jd < 1 || $jd > 31) {
            throw new \InvalidArgumentException('Invalid Jalali date.');
        }

        if ($jy > 979) {
            $gy = 1600;
            $jy -= 979;
        } else {
            $gy = 621;
        }

        $days = (365 * $jy)
            + (intdiv($jy, 33) * 8)
            + intdiv(($jy % 33) + 3, 4)
            + 78
            + $jd;

        $days += $jm < 7
            ? ($jm - 1) * 31
            : (($jm - 7) * 30) + 186;

        $gy += 400 * intdiv($days, 146097);
        $days %= 146097;

        if ($days > 36524) {
            $gy += 100 * intdiv(--$days, 36524);
            $days %= 36524;

            if ($days >= 365) {
                $days++;
            }
        }

        $gy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $gy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        $gd = $days + 1;
        $leap = ($gy % 4 === 0 && $gy % 100 !== 0) || $gy % 400 === 0;
        $daysInGregorianMonth = [
            0, 31, $leap ? 29 : 28, 31, 30, 31, 30,
            31, 31, 30, 31, 30, 31,
        ];

        for ($gm = 1; $gm <= 12 && $gd > $daysInGregorianMonth[$gm]; $gm++) {
            $gd -= $daysInGregorianMonth[$gm];
        }

        if ($gm > 12) {
            throw new \InvalidArgumentException('Invalid converted Gregorian date.');
        }

        return Carbon::create($gy, $gm, $gd, 0, 0, 0, config('app.timezone'));
    }

    public static function monthName(int $month): string
    {
        return self::MONTHS[$month - 1] ?? '';
    }

    public static function digits(int|string $value): string
    {
        return strtr((string) $value, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }

    public static function number(int|float|string|null $value, int $decimals = 0): string
    {
        $formatted = number_format((float) ($value ?? 0), $decimals, '٫', '٬');

        return self::digits($formatted);
    }

    public static function format(CarbonInterface $date, string $style = 'full'): string
    {
        [$year, $month, $day] = self::fromGregorian($date);
        $monthName = self::monthName($month);
        $dayText = self::digits($day);
        $yearText = self::digits($year);

        return match ($style) {
            'day-month' => $dayText . ' ' . $monthName,
            'month' => $monthName,
            'month-year' => $monthName . ' ' . $yearText,
            'year' => $yearText,
            'datetime' => $dayText . ' ' . $monthName . ' ' . $yearText . '، '
                . self::digits($date->format('H:i')),
            default => self::WEEKDAYS[$date->dayOfWeek]
                . '، ' . $dayText . ' ' . $monthName . ' ' . $yearText,
        };
    }
}
