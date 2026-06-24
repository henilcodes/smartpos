<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\Dashboard;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'admin@smartpos.local')->firstOrFail();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($admin);
        Filament::auth()->login($admin);
    }

    public function test_dashboard_page_loads_with_widgets(): void
    {
        Livewire::test(Dashboard::class)
            ->assertSuccessful()
            ->assertSee('Dashboard');
    }
}
