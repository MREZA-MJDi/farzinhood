<?php

namespace Tests\Unit;

use App\Support\JalaliCalendar;
use Carbon\Carbon;
use Tests\TestCase;

class JalaliCalendarTest extends TestCase
{
    public function test_gregorian_date_is_converted_to_the_jalali_calendar(): void
    {
        $date = Carbon::create(2026, 10, 19, 12, 0, 0, 'UTC');

        $this->assertSame([1405, 7, 27], JalaliCalendar::fromGregorian($date));
    }

    public function test_jalali_new_year_boundary_converts_to_gregorian_correctly(): void
    {
        $this->assertSame(
            '2026-03-21',
            JalaliCalendar::toGregorian(1405, 1, 1)->format('Y-m-d')
        );
    }

    public function test_persian_number_format_uses_persian_digits_and_grouping(): void
    {
        $this->assertSame('۱۲۳٬۴۵۶٬۷۸۹', JalaliCalendar::number(123456789));
    }

    public function test_dashboard_date_label_is_jalali_and_persian(): void
    {
        $date = Carbon::create(2026, 10, 19, 12, 0, 0, 'UTC');

        $this->assertSame('دوشنبه، ۲۷ مهر ۱۴۰۵', JalaliCalendar::format($date, 'full'));
    }
}
