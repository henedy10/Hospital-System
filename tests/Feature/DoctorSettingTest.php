<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DoctorSettingTest extends TestCase
{
    use RefreshDatabase;

    protected $doctor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->doctor = User::factory()->create([
            'role' => User::ROLE_DOCTOR,
            'specialist' => 'Cardiologist',
            'bio' => 'Sample bio',
        ]);
    }

    public function test_doctor_can_view_settings_page()
    {
        $response = $this->actingAs($this->doctor)->get(route('doctor.settings'));

        $response->assertStatus(200);
        $response->assertSee($this->doctor->name);
        $response->assertSee('Cardiologist');
    }

    public function test_doctor_can_update_profile_information()
    {
        $response = $this->actingAs($this->doctor)->post(route('doctor.settings.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@hospital.com',
            'specialist' => 'Neurologist',
            'bio' => 'New bio text',
        ]);

        $response->assertStatus(302);
        $this->doctor->refresh();

        $this->assertEquals('Updated Name', $this->doctor->name);
        $this->assertEquals('updated@hospital.com', $this->doctor->email);
        $this->assertEquals('Neurologist', $this->doctor->specialist);
        $this->assertEquals('New bio text', $this->doctor->bio);
    }

    public function test_doctor_can_change_password()
    {
        $response = $this->actingAs($this->doctor)->post(route('doctor.settings.password'), [
            'current_password' => 'password', // default factory password
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(302);
        $this->doctor->refresh();

        $this->assertTrue(Hash::check('new-password', $this->doctor->password));
    }

    public function test_doctor_can_update_notification_settings()
    {
        $response = $this->actingAs($this->doctor)->post(route('doctor.settings.notifications'), [
            'email' => 'on',
            'reports' => 'on',
        ]);

        $response->assertStatus(302);
        $this->doctor->refresh();

        $this->assertTrue($this->doctor->notification_settings['email']);
        $this->assertFalse($this->doctor->notification_settings['sms']);
        $this->assertTrue($this->doctor->notification_settings['reports']);
    }
}
