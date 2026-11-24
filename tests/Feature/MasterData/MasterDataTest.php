<?php

namespace Tests\Feature\MasterData;

use App\Models\User;
use Database\Seeders\System\PermissionSeeder;
use Database\Seeders\System\RolePermissionSeeder;
use Database\Seeders\System\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    private const HOST = 'http://app.meditech.local';

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $this->seed(RolePermissionSeeder::class);
    }

    private function userWithRole(string $roleName, ?string $email = null): User
    {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test ' . $roleName,
            'email' => $email ?? str($roleName)->slug() . '@meditech.test',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ], 'user_id');

        DB::table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => (int) DB::table('roles')->where('role_name', $roleName)->value('role_id'),
            'assigned_by' => null,
            'assigned_at' => now(),
        ]);

        return User::findOrFail($userId);
    }

    private function readOnlyUser(): User
    {
        $roleId = DB::table('roles')->insertGetId([
            'role_name' => 'Viewer Master',
            'description' => 'Hanya melihat master data.',
            'is_system_role' => false,
            'is_super_admin' => false,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'role_id');

        DB::table('role_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => (int) DB::table('permissions')->where('permission_code', 'master_data.read')->value('permission_id'),
            'granted_by' => null,
            'granted_at' => now(),
        ]);

        return $this->userWithRole('Viewer Master', 'viewer@meditech.test');
    }

    private function seedCategory(string $code = 'CAT-001', string $name = 'Hardware'): int
    {
        return DB::table('product_categories')->insertGetId([
            'category_code' => $code,
            'category_name' => $name,
            'is_active' => true,
        ], 'category_id');
    }

    private function seedProduct(int $categoryId, string $sku = 'PRD-001'): int
    {
        return DB::table('products')->insertGetId([
            'sku' => $sku,
            'product_name' => 'Laptop Lenovo ThinkPad E14',
            'category_id' => $categoryId,
            'product_type' => 'Barang',
            'unit' => 'Unit',
            'selling_price' => 13500000,
            'purchase_price' => 9720000,
            'min_stock' => 5,
            'track_serial' => true,
            'is_active' => true,
        ], 'product_id');
    }

    private function seedCustomer(string $code = 'CUS-0001', string $name = 'PT Bank Sentosa Prima'): int
    {
        return DB::table('customers')->insertGetId([
            'customer_code' => $code,
            'name' => $name,
            'industry_sector' => 'Perbankan',
            'address' => 'Jl. MH Thamrin No. 40, Jakarta Pusat',
            'phone' => '021-3190-5500',
            'email' => 'logistik@banksentosa.id',
            'is_active' => true,
        ], 'customer_id');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(self::HOST . '/master-data/customers')->assertRedirect(self::HOST . '/login');
    }

    public function test_user_without_master_data_permission_gets_403(): void
    {
        $user = $this->userWithRole('Admin CMS');

        $this->actingAs($user)->get(self::HOST . '/master-data/customers')->assertForbidden();
    }

    public function test_master_data_root_redirects_to_customers_tab(): void
    {
        $user = $this->userWithRole('Sales');

        $this->actingAs($user)->get(self::HOST . '/master-data')
            ->assertRedirect(route('master-data.customers.index'));
    }

    public function test_every_tab_renders_with_its_data(): void
    {
        $user = $this->userWithRole('Super Admin');
        $categoryId = $this->seedCategory();
        $this->seedProduct($categoryId);
        $this->seedCustomer();
        DB::table('suppliers')->insert(['supplier_code' => 'SUP-0001', 'name' => 'PT Distribusi Komputer', 'phone' => '021-1', 'address' => 'Jakarta', 'is_active' => true]);
        DB::table('warehouses')->insert(['warehouse_code' => 'WH-001', 'warehouse_name' => 'Gudang Utama', 'location' => 'Tangerang', 'is_active' => true]);
        DB::table('bank_accounts')->insert(['account_code' => 'BNK-001', 'bank_name' => 'BCA', 'account_holder' => 'PT Meditech', 'account_number' => '1234567890', 'purpose' => 'Operasional', 'is_active' => true]);

        $expectations = [
            'customers' => 'PT Bank Sentosa Prima',
            'suppliers' => 'PT Distribusi Komputer',
            'products' => 'Laptop Lenovo ThinkPad E14',
            'categories' => 'Hardware',
            'warehouses' => 'Gudang Utama',
            'bank-accounts' => '1234567890',
        ];

        foreach ($expectations as $tab => $text) {
            $this->actingAs($user)->get(self::HOST . '/master-data/' . $tab)
                ->assertOk()
                ->assertSee('Master Data')
                ->assertSee($text);
        }
    }

    public function test_search_filters_rows(): void
    {
        $user = $this->userWithRole('Sales');
        $this->seedCustomer();
        $this->seedCustomer('CUS-0002', 'RS Harapan Sehat');

        $this->actingAs($user)->get(self::HOST . '/master-data/customers?search=harapan')
            ->assertOk()
            ->assertSee('RS Harapan Sehat')
            ->assertDontSee('PT Bank Sentosa Prima');
    }

    public function test_read_only_user_sees_notice_and_cannot_create(): void
    {
        $user = $this->readOnlyUser();

        $this->actingAs($user)->get(self::HOST . '/master-data/customers')
            ->assertOk()
            ->assertSee('hanya dapat melihat')
            ->assertDontSee('Tambah customer');

        $this->actingAs($user)->get(self::HOST . '/master-data/customers/create')->assertForbidden();
        $this->actingAs($user)->post(self::HOST . '/master-data/customers', [])->assertForbidden();
    }

    public function test_sales_can_create_customer_with_generated_code_and_audit_log(): void
    {
        $user = $this->userWithRole('Sales');
        $this->seedCustomer('CUS-0007', 'PT Lama');

        $this->actingAs($user)->post(self::HOST . '/master-data/customers', [
            'name' => 'SMK Teknologi Nusantara',
            'industry_sector' => 'Pendidikan',
            'phone' => '021-555-0100',
            'address' => 'Jl. Raya Serpong No. 12',
            'email' => 'sarpras@smktn.sch.id',
        ])->assertRedirect(route('master-data.customers.index'));

        $this->assertDatabaseHas('customers', [
            'customer_code' => 'CUS-0008',
            'name' => 'SMK Teknologi Nusantara',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->user_id,
            'action' => 'CREATE_MASTER_DATA',
            'entity_name' => 'CUSTOMERS',
        ]);
    }

    public function test_customer_validation_rejects_duplicate_name_and_bad_email(): void
    {
        $user = $this->userWithRole('Sales');
        $this->seedCustomer();

        $this->actingAs($user)->post(self::HOST . '/master-data/customers', [
            'name' => 'pt bank sentosa prima',
            'industry_sector' => 'Perbankan',
            'phone' => '021',
            'address' => 'Jakarta',
            'email' => 'bukan-email',
        ])->assertSessionHasErrors(['name', 'email']);
    }

    public function test_sales_can_update_customer_but_code_stays_the_same(): void
    {
        $user = $this->userWithRole('Sales');
        $customerId = $this->seedCustomer();

        $this->actingAs($user)->get(self::HOST . "/master-data/customers/{$customerId}/edit")
            ->assertOk()->assertSee('CUS-0001');

        $this->actingAs($user)->put(self::HOST . "/master-data/customers/{$customerId}", [
            'customer_code' => 'HACK-1',
            'name' => 'PT Bank Sentosa Prima Tbk',
            'industry_sector' => 'Perbankan',
            'phone' => '021-3190-5500',
            'address' => 'Jl. MH Thamrin No. 40',
            'email' => 'logistik@banksentosa.id',
        ])->assertRedirect(route('master-data.customers.index'));

        $this->assertDatabaseHas('customers', [
            'customer_id' => $customerId,
            'customer_code' => 'CUS-0001',
            'name' => 'PT Bank Sentosa Prima Tbk',
        ]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'UPDATE_MASTER_DATA', 'entity_id' => (string) $customerId]);
    }

    public function test_soft_delete_requires_soft_delete_permission(): void
    {
        $customerId = $this->seedCustomer();

        $this->actingAs($this->userWithRole('Sales'))
            ->delete(self::HOST . "/master-data/customers/{$customerId}", ['deletion_reason' => 'Data ganda di sistem'])
            ->assertForbidden();

        $this->assertDatabaseHas('customers', ['customer_id' => $customerId, 'deleted_at' => null]);
    }

    public function test_super_admin_soft_deletes_unused_record_and_it_disappears(): void
    {
        $admin = $this->userWithRole('Super Admin');
        $customerId = $this->seedCustomer();

        $this->actingAs($admin)
            ->delete(self::HOST . "/master-data/customers/{$customerId}", ['deletion_reason' => 'Data ganda di sistem'])
            ->assertRedirect(route('master-data.customers.index'));

        $row = DB::table('customers')->where('customer_id', $customerId)->first();
        $this->assertNotNull($row->deleted_at);
        $this->assertSame($admin->user_id, (int) $row->deleted_by);
        $this->assertSame('Data ganda di sistem', $row->deletion_reason);
        $this->assertDatabaseHas('audit_logs', ['action' => 'SOFT_DELETE_MASTER_DATA', 'entity_name' => 'CUSTOMERS']);

        // Kode tidak muncul di pesan flash, jadi aman dipakai untuk memastikan baris tidak tampil lagi.
        $this->actingAs($admin)->get(self::HOST . '/master-data/customers')
            ->assertOk()->assertDontSee('CUS-0001');
    }

    public function test_soft_delete_is_blocked_when_record_is_still_used(): void
    {
        $admin = $this->userWithRole('Super Admin');
        $categoryId = $this->seedCategory();
        $this->seedProduct($categoryId);

        $this->actingAs($admin)
            ->delete(self::HOST . "/master-data/categories/{$categoryId}", ['deletion_reason' => 'Tidak dipakai lagi'])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('product_categories', ['category_id' => $categoryId, 'deleted_at' => null]);
    }

    public function test_warehouse_can_create_product_and_toggle_its_status(): void
    {
        $user = $this->userWithRole('Warehouse');
        $categoryId = $this->seedCategory();

        $this->actingAs($user)->post(self::HOST . '/master-data/products', [
            'product_name' => 'Printer HP LaserJet Pro M404dn',
            'category_id' => $categoryId,
            'product_type' => 'Barang',
            'unit' => 'Unit',
            'selling_price' => 5200000,
            'purchase_price' => 3744000,
            'min_stock' => 10,
            'track_serial' => '1',
            'is_active' => '1',
        ])->assertRedirect(route('master-data.products.index'));

        $productId = (int) DB::table('products')->where('sku', 'PRD-001')->value('product_id');
        $this->assertGreaterThan(0, $productId);

        $this->actingAs($user)->patch(self::HOST . "/master-data/products/{$productId}/status")
            ->assertRedirect(route('master-data.products.index'));

        $this->assertDatabaseHas('products', ['product_id' => $productId, 'is_active' => false]);
    }

    public function test_service_product_stores_zero_minimum_stock(): void
    {
        $user = $this->userWithRole('Warehouse');
        $categoryId = $this->seedCategory('CAT-002', 'Jasa IT');

        $this->actingAs($user)->post(self::HOST . '/master-data/products', [
            'product_name' => 'Instalasi CCTV per titik',
            'category_id' => $categoryId,
            'product_type' => 'Jasa',
            'unit' => 'Titik',
            'selling_price' => 350000,
            'purchase_price' => 0,
            'min_stock' => 7,
        ])->assertRedirect(route('master-data.products.index'));

        $this->assertDatabaseHas('products', ['product_name' => 'Instalasi CCTV per titik', 'min_stock' => 0, 'track_serial' => false]);
    }

    public function test_finance_can_create_bank_account(): void
    {
        $user = $this->userWithRole('Finance');

        $this->actingAs($user)->post(self::HOST . '/master-data/bank-accounts', [
            'bank_name' => 'Mandiri',
            'purpose' => 'Penerimaan',
            'account_holder' => 'PT Meditech Cipta Inovasi',
            'account_number' => '1230009876543',
        ])->assertRedirect(route('master-data.bank-accounts.index'));

        $this->assertDatabaseHas('bank_accounts', ['account_code' => 'BNK-001', 'account_number' => '1230009876543']);
    }

    public function test_categories_warehouses_and_suppliers_can_be_created(): void
    {
        $user = $this->userWithRole('Super Admin');

        $this->actingAs($user)->post(self::HOST . '/master-data/categories', ['category_name' => 'Networking'])
            ->assertRedirect(route('master-data.categories.index'));
        $this->actingAs($user)->post(self::HOST . '/master-data/warehouses', ['warehouse_name' => 'Gudang Serpong', 'location' => 'Tangerang Selatan'])
            ->assertRedirect(route('master-data.warehouses.index'));
        $this->actingAs($user)->post(self::HOST . '/master-data/suppliers', ['name' => 'PT Sinar Komputer', 'phone' => '021-777', 'address' => 'Jakarta'])
            ->assertRedirect(route('master-data.suppliers.index'));

        $this->assertDatabaseHas('product_categories', ['category_code' => 'CAT-001', 'category_name' => 'Networking']);
        $this->assertDatabaseHas('warehouses', ['warehouse_code' => 'WH-001', 'warehouse_name' => 'Gudang Serpong']);
        $this->assertDatabaseHas('suppliers', ['supplier_code' => 'SUP-0001', 'name' => 'PT Sinar Komputer']);
    }
}
