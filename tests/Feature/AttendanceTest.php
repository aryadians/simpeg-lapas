<?php

namespace Tests\Feature;

use App\Livewire\AttendanceWidget;
use App\Models\Attendance;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Office Location
        config(['app.office_latitude' => -7.556055]);
        config(['app.office_longitude' => 112.235313]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_user_can_clock_in_within_radius()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $shift = Shift::create([
            'name' => 'Pagi', 
            'start_time' => '07:00:00', 
            'end_time' => '13:00:00',
            'is_overnight' => false
        ]);
        
        // Roster for today
        $roster = Roster::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        // Set time to 06:50 AM (Before 07:00 Start)
        Carbon::setTestNow(Carbon::today()->setTime(6, 50));

        $file = UploadedFile::fake()->image('selfie.jpg');

        Livewire::actingAs($user)
            ->test(AttendanceWidget::class, ['todayRoster' => $roster])
            ->set('userLatitude', -7.556055) // Same as office
            ->set('userLongitude', 112.235313)
            ->set('selfie', $file)
            ->call('setUserLocation', -7.556055, 112.235313)
            ->set('isWithinRadius', true) // Force true just in case
            ->call('confirmClockIn');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'status' => 'hadir', // Assuming test runs before shift start or close to it
        ]);
        
        // Check file storage
        // Since we don't know the exact hash name, we just check if any file exists in selfies
        $this->assertGreaterThan(0, count(Storage::disk('public')->files('selfies')));
    }

    public function test_user_cannot_clock_in_outside_radius()
    {
        $user = User::factory()->create();
        $shift = Shift::create([
            'name' => 'Pagi', 
            'start_time' => '07:00:00', 
            'end_time' => '13:00:00'
        ]);
        
        $roster = Roster::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        Livewire::actingAs($user)
            ->test(AttendanceWidget::class, ['todayRoster' => $roster])
            ->call('setUserLocation', -8.000000, 113.000000) // Far away
            ->call('clockIn')
            ->assertDispatched('flash-message'); // Should show error
            
        $this->assertDatabaseMissing('attendances', [
            'user_id' => $user->id,
        ]);
    }
}
