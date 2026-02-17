<?php

namespace Tests\Feature;

use App\Livewire\RosterDashboard;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Carbon\Carbon;

class RosterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed basic shifts
        Shift::create(['id' => 1, 'name' => 'Pagi', 'start_time' => '07:00:00', 'end_time' => '13:00:00']);
        Shift::create(['id' => 2, 'name' => 'Siang', 'start_time' => '13:00:00', 'end_time' => '19:00:00']);
        Shift::create(['id' => 3, 'name' => 'Malam', 'start_time' => '19:00:00', 'end_time' => '07:00:00', 'is_overnight' => true]);
    }

    public function test_dashboard_can_render()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/')
            ->assertStatus(200);
    }

    public function test_roster_data_is_visible()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $shift = Shift::find(1);
        $date = Carbon::today()->format('Y-m-d');

        Roster::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'date' => $date,
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertSee($user->name)
            ->assertSee($shift->name);
    }

    public function test_admin_can_generate_schedule()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        // Create some users to generate schedule for
        User::factory()->count(3)->create();

        Livewire::actingAs($admin)
            ->test(RosterDashboard::class)
            ->call('generateSchedule');

        // Check if rosters were created for the current month
        $this->assertGreaterThan(0, Roster::whereMonth('date', Carbon::now()->month)->count());
    }

    public function test_non_admin_cannot_generate_schedule()
    {
        $user = User::factory()->create(['role' => 'staff']);

        Livewire::actingAs($user)
            ->test(RosterDashboard::class)
            ->call('generateSchedule');

        // Should be no rosters if user tries to generate
        $this->assertDatabaseCount('rosters', 0);
    }
}
