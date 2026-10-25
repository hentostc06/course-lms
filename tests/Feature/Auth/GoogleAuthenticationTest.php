<?php

namespace Tests\Feature\Auth;

use App\Domain\Identity\Enums\RoleName;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'test-client',
            'services.google.client_secret' => 'test-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    public function test_verified_google_account_creates_a_verified_student(): void
    {
        $this->mockGoogleUser('student@gmail.com', ['email_verified' => true]);

        $this->get(route('auth.google.callback'))->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'student@gmail.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertTrue($user->hasRole(RoleName::Student));
        $this->assertFalse($user->hasRole(RoleName::Admin));
    }

    /** @param array<string, bool|string> $claims */
    #[DataProvider('unverifiedClaims')]
    public function test_unverified_or_missing_email_claim_never_logs_in_or_changes_an_existing_user(array $claims): void
    {
        $user = User::factory()->unverified()->create(['email' => 'student@gmail.com']);
        $this->mockGoogleUser($user->email, $claims);

        $this->get(route('auth.google.callback'))->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $this->assertFalse($user->hasRole(RoleName::Student));
        $this->assertDatabaseCount('users', 1);
    }

    /** @return array<string, array{array<string, bool|string>}> */
    public static function unverifiedClaims(): array
    {
        return [
            'false' => [['email_verified' => false]],
            'string false' => [['email_verified' => 'false']],
            'missing' => [[]],
        ];
    }

    public function test_verified_external_email_without_workspace_is_not_linked(): void
    {
        $user = User::factory()->create(['email' => 'student@example.test']);
        $this->mockGoogleUser($user->email, ['email_verified' => true]);

        $this->get(route('auth.google.callback'))->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_google_does_not_verify_a_preexisting_local_account_with_an_untrusted_password(): void
    {
        $user = User::factory()->unverified()->create(['email' => 'student@gmail.com']);
        $passwordHash = $user->password;
        $this->mockGoogleUser($user->email, ['email_verified' => true]);

        $this->get(route('auth.google.callback'))->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $this->assertSame($passwordHash, $user->fresh()->password);
        $this->assertFalse($user->hasRole(RoleName::Student));
        $this->assertDatabaseCount('users', 1);
    }

    public function test_verified_workspace_email_can_log_in_to_existing_account(): void
    {
        $user = User::factory()->create(['email' => 'student@example.test']);
        $this->mockGoogleUser($user->email, ['email_verified' => true, 'hd' => 'example.test']);

        $this->get(route('auth.google.callback'))->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_google_login_requires_enabled_two_factor_before_authentication(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
        $user = User::factory()->withTwoFactor()->create(['email' => 'student@gmail.com']);
        $this->mockGoogleUser($user->email, ['email_verified' => true]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('two-factor.login'))
            ->assertSessionHas('login.id', $user->id)
            ->assertSessionHas('login.remember', true);

        $this->assertGuest();
        $this->post(route('two-factor.login.store'), ['recovery_code' => 'recovery-code-1'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    /** @param array<string, bool|string> $claims */
    private function mockGoogleUser(string $email, array $claims): void
    {
        $googleUser = (new GoogleUser)->setRaw($claims)->map([
            'id' => 'test-google-subject',
            'name' => 'Google Student',
            'email' => $email,
        ]);

        Socialite::shouldReceive('driver')->once()->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->once()->andReturn($googleUser);
    }
}
