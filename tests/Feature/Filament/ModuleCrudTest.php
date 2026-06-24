<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Brands\BrandResource;
use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\CategoryGroups\CategoryGroupResource;
use App\Filament\Resources\CategoryGroups\Pages\ListCategoryGroups;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\Inventories\Pages\ListInventories;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Sessions\SessionResource;
use App\Filament\Resources\Sessions\Pages\ListSessions;
use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Suppliers\SupplierResource;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\TaxGroups\TaxGroupResource;
use App\Filament\Resources\TaxGroups\Pages\ListTaxGroups;
use App\Filament\Resources\Taxes\TaxResource;
use App\Filament\Resources\Taxes\Pages\CreateTax;
use App\Filament\Resources\Taxes\Pages\ListTaxes;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ModuleCrudTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->admin = User::query()->where('email', 'admin@smartpos.local')->firstOrFail();
        $this->authenticateAdmin();
    }

    /**
     * @return array<string, array{list: class-string, create: class-string|null, resource: class-string}>
     */
    public static function modulePagesProvider(): array
    {
        return [
            'products' => ['list' => ListProducts::class, 'create' => null, 'resource' => ProductResource::class],
            'categories' => ['list' => ListCategories::class, 'create' => CreateCategory::class, 'resource' => CategoryResource::class],
            'category_groups' => ['list' => ListCategoryGroups::class, 'create' => null, 'resource' => CategoryGroupResource::class],
            'brands' => ['list' => ListBrands::class, 'create' => CreateBrand::class, 'resource' => BrandResource::class],
            'inventories' => ['list' => ListInventories::class, 'create' => null, 'resource' => InventoryResource::class],
            'orders' => ['list' => ListOrders::class, 'create' => null, 'resource' => OrderResource::class],
            'customers' => ['list' => ListCustomers::class, 'create' => CreateCustomer::class, 'resource' => CustomerResource::class],
            'suppliers' => ['list' => ListSuppliers::class, 'create' => CreateSupplier::class, 'resource' => SupplierResource::class],
            'taxes' => ['list' => ListTaxes::class, 'create' => CreateTax::class, 'resource' => TaxResource::class],
            'tax_groups' => ['list' => ListTaxGroups::class, 'create' => null, 'resource' => TaxGroupResource::class],
            'users' => ['list' => ListUsers::class, 'create' => null, 'resource' => UserResource::class],
            'sessions' => ['list' => ListSessions::class, 'create' => null, 'resource' => SessionResource::class],
        ];
    }

    protected function authenticateAdmin(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($this->admin);
        Filament::auth()->login($this->admin);
    }

    #[DataProvider('modulePagesProvider')]
    public function test_module_list_pages_load(string $list, ?string $create, string $resource): void
    {
        Livewire::test($list)->assertSuccessful();

        if ($create !== null) {
            Livewire::test($create)->assertSuccessful();
        }
    }

    public function test_brand_crud_lifecycle(): void
    {
        $name = 'CRUD Test Brand ' . Str::random(4);

        Livewire::test(CreateBrand::class)
            ->fillForm([
                'name' => $name,
                'slug' => Str::slug($name),
                'website' => 'https://example.com',
                'is_visible' => true,
                'featured' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $brand = Brand::query()->where('name', $name)->first();
        $this->assertNotNull($brand);

        Livewire::test(EditBrand::class, ['record' => $brand->getRouteKey()])
            ->fillForm([
                'website' => 'https://updated.example.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $brand->refresh();
        $this->assertSame('https://updated.example.com', $brand->website);

        $brand->delete();
        $this->assertSoftDeleted($brand);
    }

    public function test_category_crud_lifecycle(): void
    {
        $name = 'CRUD Category ' . Str::random(4);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => $name,
                'slug' => Str::slug($name),
                'featured' => false,
                'is_visible' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::query()->where('name', $name)->first();
        $this->assertNotNull($category);
        $category->delete();
        $this->assertSoftDeleted($category);
    }

    public function test_tax_crud_lifecycle(): void
    {
        $name = 'CRUD Tax ' . Str::random(4);

        Livewire::test(CreateTax::class)
            ->fillForm([
                'name' => $name,
                'type' => 'percentage',
                'rate' => 9,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $tax = Tax::query()->where('name', $name)->first();
        $this->assertNotNull($tax);
        $tax->delete();
        $this->assertDatabaseMissing('taxes', ['id' => $tax->id]);
    }

    public function test_supplier_crud_lifecycle(): void
    {
        $phone = '9' . random_int(100000000, 999999999);

        Livewire::test(CreateSupplier::class)
            ->fillForm([
                'name' => 'CRUD Supplier',
                'phone' => $phone,
                'email' => 'supplier-crud@example.com',
                'address' => 'Test address',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $supplier = Supplier::query()->where('phone', $phone)->first();
        $this->assertNotNull($supplier);
        $supplier->delete();
        $this->assertSoftDeleted($supplier);
    }

    public function test_customer_crud_lifecycle(): void
    {
        $phone = '8' . random_int(100000000, 999999999);

        Livewire::test(CreateCustomer::class)
            ->fillForm([
                'phone' => $phone,
                'name' => 'CRUD Customer',
                'email' => 'customer-crud@example.com',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $customer = Customer::query()->where('phone', $phone)->first();
        $this->assertNotNull($customer);
        $customer->delete();
        $this->assertSoftDeleted($customer);
    }

    public function test_settings_page_loads(): void
    {
        Livewire::test(ManageSettings::class)->assertSuccessful();
    }
}
