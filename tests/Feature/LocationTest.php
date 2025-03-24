<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Data provider for edit permissions
     */
    public static function provideEditPermissions()
    {
        return [
            'Admin can edit if owns team of location' => ['admin', true, false, true],
            'Admin can edit if belongs to team of location' => ['admin', false, true, true],
            'Editor can edit if owns team of location' => ['editor', true, false, true],
            'Editor can edit if belongs team of location' => ['editor', false, true, true],
            'Non-team member cannot edit' => ['', false, false, false],
        ];
    }

    /**
     * Test edit permissions
     * @dataProvider provideEditPermissions
     */
    public function test_user_can_edit_location($role, $isOwnerOfTeam, $belongsToTeam, $expectedResult)
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $userTeam = Team::factory()->create(['user_id' => $user->id]);
        $user->current_team_id = $userTeam->id;

        if($isOwnerOfTeam){
            //Location as same team_id as user
            $location = Location::factory()->create([
                'team_id' => $user->current_team_id,
            ]);
        }else{
            $location = Location::factory()->create([
                'team_id' => $team->id,
            ]);
        }
        
        if ($belongsToTeam) {
            $team->users()->attach($user, ['role' => $role]);
        }

        // Simulate the permission check
        $canEdit = $user->hasTeamPermission($location->team, 'update');

        // Assert the result
        $this->assertEquals($expectedResult, $canEdit);
    }

    /**
     * Data provider for delete permissions
     */
    public static function provideDeletePermissions()
    {
        return [
            'Admin can delete if owns team of location' => ['admin', true, false, true],
            'Admin can delete if belongs to team of location' => ['admin', false, true, true],
            'Editor can delete if owns team of location' => ['editor', true, false, true],
            'Editor cannot delete if belongs team of location' => ['editor', false, true, false],
            'Non-team member cannot delete' => ['', false, false, false],
        ];
    }

    /**
     * Test delete permissions
     * @dataProvider provideDeletePermissions
     */
    public function test_user_can_delete_location($role, $isOwnerOfTeam, $belongsToTeam, $expectedResult)
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $userTeam = Team::factory()->create(['user_id' => $user->id]);
        $user->current_team_id = $userTeam->id;

        if($isOwnerOfTeam){
            //Location as same team_id as user
            $location = Location::factory()->create([
                'team_id' => $user->current_team_id,
            ]);
        }else{
            $location = Location::factory()->create([
                'team_id' => $team->id,
            ]);
        }
        
        if ($belongsToTeam) {
            $team->users()->attach($user, ['role' => $role]);
        }

        // Simulate the permission check
        $canDelete = $user->hasTeamPermission($location->team, 'delete');

        // Assert the result
        $this->assertEquals($expectedResult, $canDelete);
    }




    /*public function test_user_can_create_location()
    {
        $response = $this->post('/locations', [
            'name' => 'Test Location',
            'description' => 'A description for the test location',
            'latitude' => '48.8566',
            'longitude' => '2.3522',
            'is_featured' => false,
        ]);

        $response->assertStatus(302); // Vérifie la redirection
        $this->assertDatabaseHas('locations', [
            'name' => 'Test Location',
            'latitude' => '48.8566',
            'longitude' => '2.3522',
        ]);
    }*/
}
