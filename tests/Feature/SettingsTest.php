<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::where('email', 'admin@eighteen.com')->first();
    }

    public function test_user_can_access_settings_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('settings.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.edit');
        $response->assertViewHas('user');
    }

    public function test_user_can_update_profile_info()
    {
        $response = $this->actingAs($this->user)
            ->put(route('settings.update'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '0899999999',
                'whatsapp_number' => '6281234567890',
            ]);

        $response->assertRedirect(route('settings.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '0899999999',
        ]);
    }

    public function test_user_can_change_password_with_correct_current_password()
    {
        $response = $this->actingAs($this->user)
            ->put(route('settings.update'), [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'whatsapp_number' => '6281234567890',
                'current_password' => 'password',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('settings.edit'));
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password()
    {
        $response = $this->actingAs($this->user)
            ->put(route('settings.update'), [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'whatsapp_number' => '6281234567890',
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertFalse(Hash::check('newpassword123', $this->user->fresh()->password));
    }
}
