<?php

namespace Tests\Feature;

use App\Mail\AdminToUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;


    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    /** @test */
    public function admin_can_send_email_to_user()
    {
        Mail::fake();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.emails.store'), [
                'recipients' => [$this->user->id],
                'subject' => 'Test Subject',
                'message' => 'Test Message'
            ]);

        Mail::assertQueued(AdminToUserMail::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });

        $this->assertDatabaseHas('email_logs', [
            'sender_id' => $this->admin->id,
            'recipient_id' => $this->user->id,
            'status' => 'success'
        ]);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');
    }

    /** @test */
    public function requires_authentication()
    {
        $response = $this->post(route('admin.emails.store'), [
            'recipients' => [$this->user->id],
            'subject' => 'Test',
            'message' => 'Test'
        ]);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function requires_admin_role()
    {
        $regularUser = User::factory()->create();
        $regularUser->assignRole('user');

        $response = $this->actingAs($regularUser)
            ->post(route('admin.emails.store'), [
                'recipients' => [$this->user->id],
                'subject' => 'Test',
                'message' => 'Test'
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function user_without_any_role_cannot_send_emails()
    {
        $noRoleUser = User::factory()->create();

        $response = $this->actingAs($noRoleUser)
            ->post(route('admin.emails.store'), [
                'recipients' => [$this->user->id],
                'subject' => 'Test',
                'message' => 'Test'
            ]);

        $response->assertForbidden();
    }
}
