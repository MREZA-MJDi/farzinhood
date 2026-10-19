<?php

namespace Tests\Feature;

use App\Models\HomeTourFrame;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomeTourManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::query()->create([
            'name' => 'ادمین تور',
            'email' => 'tour-admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guests_cannot_open_tour_management(): void
    {
        $this->get(route('admin.home-tour.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_upload_describe_and_configure_tour_frames_for_homepage(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.home-tour.store'), [
                'scene' => 'hood',
                'images' => [
                    UploadedFile::fake()->image('hood-angle-01.jpg', 1600, 1200),
                    UploadedFile::fake()->image('hood-angle-02.jpg', 1600, 1200),
                ],
            ])
            ->assertRedirect(route('admin.home-tour.index'));

        $this->assertDatabaseCount('home_tour_frames', 2);

        $frame = HomeTourFrame::query()->where('scene', 'hood')->orderBy('sort_order')->firstOrFail();
        Storage::disk('public')->assertExists($frame->image);

        $this->actingAs($admin)
            ->patch(route('admin.home-tour.update', $frame), [
                'title' => 'نمای موتور کم‌صدا',
                'description' => 'این زاویه، طراحی موتور و خطوط بدنه را نشان می‌دهد.',
                'alt' => 'نمای نزدیک هود مشکی',
                'sort_order' => 0,
                'object_position_x' => 44,
                'object_position_y' => 53,
                'scale' => '1.12',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.home-tour.index'));

        $this->assertDatabaseHas('home_tour_frames', [
            'id' => $frame->id,
            'title' => 'نمای موتور کم‌صدا',
            'description' => 'این زاویه، طراحی موتور و خطوط بدنه را نشان می‌دهد.',
            'object_position_x' => 44,
            'object_position_y' => 53,
            'is_active' => 1,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-product-tour', false)
            ->assertSee('نمای موتور کم‌صدا')
            ->assertSee('این زاویه، طراحی موتور و خطوط بدنه را نشان می‌دهد.')
            ->assertSee('--tour-position-x: 44%', false);
    }

    public function test_non_admin_cannot_change_tour_frames(): void
    {
        $customer = User::query()->create([
            'name' => 'مشتری',
            'email' => 'tour-customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $frame = HomeTourFrame::query()->create([
            'scene' => 'sink',
            'image' => 'home-tour/sink/frame.jpg',
            'title' => 'نمای اول',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($customer)
            ->patch(route('admin.home-tour.update', $frame), [
                'title' => 'تغییر غیرمجاز',
                'sort_order' => 0,
                'object_position_x' => 50,
                'object_position_y' => 50,
                'scale' => 1.03,
                'is_active' => 1,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('home_tour_frames', [
            'id' => $frame->id,
            'title' => 'نمای اول',
        ]);
    }
}
