<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_register_successfully(): void
    {
        Notification::fake();

        Role::query()->create([
            'slug' => 'student',
            'name_ar' => 'طالب',
            'dashboard_key' => 'student',
        ]);

        $response = $this->post('/register', [
            'name' => 'طالب جديد',
            'email' => 'new.student@example.com',
            'phone' => '',
            'university' => 'جامعة الاختبار',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('dashboard.redirect'));
        $this->assertAuthenticated();

        $user = User::query()->where('email', 'new.student@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->phone);
        $this->assertTrue($user->hasRole('student'));
        $this->assertDatabaseHas('user_settings', ['user_id' => $user->id]);

        Notification::assertNotSentTo($user, VerifyEmail::class);
    }
}
