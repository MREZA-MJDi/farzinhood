<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFooterSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_uses_contact_details_saved_from_admin_settings(): void
    {
        SiteSetting::setValue('phone', '021-5555-1234');
        SiteSetting::setValue('email', 'support@farzin.ir');
        SiteSetting::setValue('address', 'تهران، خیابان نمونه، پلاک ۱۰');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('tel:02155551234', false)
            ->assertSee('021-5555-1234')
            ->assertSee('mailto:support@farzin.ir', false)
            ->assertSee('support@farzin.ir')
            ->assertSee('تهران، خیابان نمونه، پلاک ۱۰');
    }

    public function test_footer_does_not_publish_sample_contact_details_when_settings_are_empty(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('tel:+982112345678', false)
            ->assertDontSee('۰۲۱-۱۲۳۴۵۶۷۸')
            ->assertDontSee('info@farzin.ir');
    }
}
