<?php

namespace Tests\Feature\Finance;

use App\Models\Finance\CashTransaction;
use App\Models\User;
use Database\Seeders\System\PermissionSeeder;
use Database\Seeders\System\RoleSeeder;
use Database\Seeders\System\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CashFlowTest extends TestCase
{
    use RefreshDatabase;

    private const INTERNAL_HOST = 'http://app.meditech.local';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $this->seed(RolePermissionSeeder::class);
    }

    private function createUserWithRole(string $email, string $roleName): User
    {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test ' . $roleName,
            'email' => $email,
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ], 'user_id');

        $roleId = (int) DB::table('roles')->where('role_name', $roleName)->value('role_id');

        DB::table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
            'assigned_by' => null,
            'assigned_at' => now(),
        ]);

        return User::findOrFail($userId);
    }

    private function createCashTransaction(array $overrides = []): CashTransaction
    {
        $userId = DB::table('users')->value('user_id') ?? 1;

        return CashTransaction::create(array_merge([
            'transaction_number' => 'CSH-2026-001',
            'transaction_date' => now()->subDay(),
            'transaction_type' => 'Cash In',
            'source_type' => 'PAYMENT',
            'category' => 'Pembayaran customer',
            'description' => 'Pembayaran INV-2026-001',
            'amount' => 30000000,
            'bank_account_id' => null,
            'payment_id' => null,
            'petty_cash_id' => null,
            'recorded_by' => $userId,
            'status' => 'Posted',
        ], $overrides));
    }

    public function test_guest_cannot_access_cash_flow(): void
    {
        $this->get(self::INTERNAL_HOST . '/cash-flow')->assertRedirect(self::INTERNAL_HOST . '/login');
    }

    public function test_user_without_permission_gets_403(): void
    {
        $user = $this->createUserWithRole('sales@test.local', 'Sales');

        $this->actingAs($user)
            ->get(self::INTERNAL_HOST . '/cash-flow')
            ->assertForbidden();
    }

    public function test_finance_role_can_access_cash_flow(): void
    {
        $user = $this->createUserWithRole('finance@test.local', 'Finance');

        $this->createCashTransaction();

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/cash-flow');

        $response->assertOk();
        $response->assertSee('Cash Flow');
        $response->assertSee('CSH-2026-001');
    }

    public function test_filter_by_type_works(): void
    {
        $user = $this->createUserWithRole('finance2@test.local', 'Finance');

        $this->createCashTransaction(['transaction_number' => 'CSH-2026-001', 'transaction_type' => 'Cash In']);
        $this->createCashTransaction(['transaction_number' => 'CSH-2026-002', 'transaction_type' => 'Cash Out', 'amount' => -2350000]);

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/cash-flow?type=Cash+Out');

        $response->assertOk();
        $response->assertSee('CSH-2026-002');
        $response->assertDontSee('CSH-2026-001');
    }

    public function test_future_month_is_not_in_month_filter_options(): void
    {
        $user = $this->createUserWithRole('finance3@test.local', 'Finance');

        $this->createCashTransaction(['transaction_date' => now()]);

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/cash-flow');

        $futureMonth = now()->addMonth()->format('Y-m');
        $response->assertOk();
        $response->assertDontSee('value="' . $futureMonth . '"');
    }

    public function test_export_returns_csv(): void
    {
        $user = $this->createUserWithRole('finance4@test.local', 'Finance');

        $this->createCashTransaction();

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/cash-flow/export');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('CSH-2026-001', $response->streamedContent());
    }

    public function test_user_without_export_permission_gets_403_on_export(): void
    {
        $user = $this->createUserWithRole('sales2@test.local', 'Sales');

        $this->actingAs($user)
            ->get(self::INTERNAL_HOST . '/cash-flow/export')
            ->assertForbidden();
    }
}