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

    private function seedPublicRoles(): void
    {
        Role::query()->create([
            'slug' => 'student',
            'name_ar' => 'طالب',
            'dashboard_key' => 'student',
        ]);

        Role::query()->create([
            'slug' => 'instructor',
            'name_ar' => 'محاضر',
            'dashboard_key' => 'instructor',
        ]);

        Role::query()->create([
            'slug' => 'super_admin',
            'name_ar' => 'مدير النظام',
            'dashboard_key' => 'admin',
        ]);
    }

    public function test_student_can_register_successfully(): void
    {
        Notification::fake();
        $this->seedPublicRoles();

        $response = $this->post('/register', [
            'account_type' => 'student',
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
        $this->assertSame('self_registered', $user->creation_source);
        $this->assertSame('active', $user->status);
        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->hasRole('instructor'));
        $this->assertFalse($user->hasRole('super_admin'));
        $this->assertDatabaseHas('user_settings', ['user_id' => $user->id]);

        Notification::assertNotSentTo($user, VerifyEmail::class);

        $this->get(route('dashboard.redirect'))->assertRedirect(route('student.home'));
    }

    public function test_instructor_can_register_and_reach_instructor_dashboard(): void
    {
        Notification::fake();
        $this->seedPublicRoles();

        $response = $this->post('/register', [
            'account_type' => 'instructor',
            'name' => 'محاضر جديد',
            'email' => 'new.instructor@example.com',
            'phone' => '',
            'university' => '',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('dashboard.redirect'));
        $this->assertAuthenticated();

        $user = User::query()->where('email', 'new.instructor@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('self_registered', $user->creation_source);
        $this->assertSame('active', $user->status);
        $this->assertTrue($user->hasRole('instructor'));
        $this->assertFalse($user->hasRole('student'));
        $this->assertFalse($user->hasRole('super_admin'));
        $this->assertCount(1, $user->roles);
        $this->assertDatabaseHas('user_settings', ['user_id' => $user->id]);

        Notification::assertNotSentTo($user, VerifyEmail::class);

        $this->get(route('dashboard.redirect'))->assertRedirect(route('instructor.home'));
    }

    public function test_registration_requires_account_type(): void
    {
        $this->seedPublicRoles();

        $response = $this->from('/register')->post('/register', [
            'name' => 'بدون نوع',
            'email' => 'missing.type@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('account_type');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'missing.type@example.com']);
    }

    public function test_registration_rejects_staff_account_type_values(): void
    {
        $this->seedPublicRoles();

        $response = $this->from('/register')->post('/register', [
            'account_type' => 'super_admin',
            'name' => 'محاولة أدمن',
            'email' => 'evil.admin@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('account_type');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'evil.admin@example.com']);
    }

    public function test_register_form_preserves_account_type_on_validation_error(): void
    {
        $this->seedPublicRoles();

        $response = $this->from('/register')->post('/register', [
            'account_type' => 'instructor',
            'name' => '',
            'email' => 'incomplete@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasInput('account_type', 'instructor');
    }

    public function test_admin_created_and_self_registered_peers_share_dashboard_keys(): void
    {
        $this->seedPublicRoles();

        $studentRole = Role::query()->where('slug', 'student')->firstOrFail();
        $instructorRole = Role::query()->where('slug', 'instructor')->firstOrFail();

        $adminStudent = User::query()->create([
            'name' => 'طالب إداري',
            'email' => 'admin.created.student@example.com',
            'password' => 'Password123!',
            'creation_source' => 'admin_created',
            'status' => 'active',
        ]);
        $adminStudent->roles()->attach($studentRole);

        $adminInstructor = User::query()->create([
            'name' => 'محاضر إداري',
            'email' => 'admin.created.instructor@example.com',
            'password' => 'Password123!',
            'creation_source' => 'admin_created',
            'status' => 'active',
        ]);
        $adminInstructor->roles()->attach($instructorRole);

        $this->post('/register', [
            'account_type' => 'student',
            'name' => 'طالب ذاتي',
            'email' => 'self.student@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('dashboard.redirect'));
        $selfStudent = User::query()->where('email', 'self.student@example.com')->firstOrFail();
        $this->post('/logout');

        $this->post('/register', [
            'account_type' => 'instructor',
            'name' => 'محاضر ذاتي',
            'email' => 'self.instructor@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('dashboard.redirect'));
        $selfInstructor = User::query()->where('email', 'self.instructor@example.com')->firstOrFail();

        $this->assertSame(['student'], $adminStudent->fresh()->dashboardKeys());
        $this->assertSame(['student'], $selfStudent->fresh()->dashboardKeys());
        $this->assertSame(['instructor'], $adminInstructor->fresh()->dashboardKeys());
        $this->assertSame(['instructor'], $selfInstructor->fresh()->dashboardKeys());
    }
}
