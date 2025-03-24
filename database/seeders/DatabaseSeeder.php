<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Créer 3 utilisateurs avec leurs propres équipes
        $basicUsers = User::factory(3)->create();
        $basicUsers->each(function ($user, $index) {
            $ownedTeam = Team::create([
                'name' => $user->name . "'s Team",
                'personal_team' => true,
                'user_id' => $user->id,
            ]);
            $user->current_team_id = $ownedTeam->id;
            $user->save();
        });

        $teams = Team::all();

        // Associer des utilisateurs supplémentaires à ces équipes avec des rôles
        $additionalUsers = User::factory(5)->create(); // Utilisateurs supplémentaires
        $additionalUsers->each(function ($user, $index) use ($teams) {
            $team = $teams->random();
            $team->users()->attach($user, ['role' => $index % 2 === 0 ? 'editor' : 'admin']);

            $ownedTeam = Team::create([
                'name' => $user->name . "'s Team",
                'personal_team' => true,
                'user_id' => $user->id,
            ]);
            $user->current_team_id = $ownedTeam->id;
            $user->save();
        });

        // Créer des locations associées aux teams
        $locations = [
            [
                'name' => 'Hyde Park',
                'description' => 'A large and famous park in central London.',
                'category' => 'park',
                'latitude' => 51.507268,
                'longitude' => -0.165730,
                'is_featured' => true,
                'team_id' => $teams->random()->id,
            ],
            [
                'name' => 'River Thames',
                'description' => 'The iconic river running through London.',
                'category' => 'river',
                'latitude' => 51.507351,
                'longitude' => -0.127758,
                'is_featured' => false,
                'team_id' => $teams->random()->id,
            ],
            [
                'name' => 'Epping Forest',
                'description' => 'A tranquil forest area on the outskirts of London.',
                'category' => 'forest',
                'latitude' => 51.651470,
                'longitude' => 0.058710,
                'is_featured' => true,
                'team_id' => $teams->random()->id,
            ],
            [
                'name' => 'Richmond Park',
                'description' => 'A vast green area with deer roaming freely.',
                'category' => 'park',
                'latitude' => 51.442833,
                'longitude' => -0.273404,
                'is_featured' => false,
                'team_id' => $teams->random()->id,
            ],
            [
                'name' => 'Lee Valley',
                'description' => 'A peaceful river valley to the northeast of London.',
                'category' => 'river',
                'latitude' => 51.676230,
                'longitude' => -0.009880,
                'is_featured' => true,
                'team_id' => $teams->random()->id,
            ],
            [
                'name' => 'Wimbledon Common',
                'description' => 'A large open space with wooded areas and ponds.',
                'category' => 'forest',
                'latitude' => 51.434980,
                'longitude' => -0.233690,
                'is_featured' => false,
                'team_id' => $teams->random()->id,
            ],
            [
                'name' => 'Kew Gardens',
                'description' => 'A botanical garden with beautiful greenhouses.',
                'category' => 'park',
                'team_id' => $teams->random()->id,
                'is_featured' => true,
                'latitude' => 51.476861,
                'longitude' => -0.296240,
            ],
            [
                'name' => 'Windsor Great Park',
                'description' => 'A historic park near Windsor Castle.',
                'category' => 'forest',
                'team_id' => $teams->random()->id,
                'is_featured' => false,
                'latitude' => 51.431585,
                'longitude' => -0.619890,
            ],
            [
                'name' => 'Sevenoaks Wildlife Reserve',
                'description' => 'A peaceful reserve with lakes and trails.',
                'category' => 'river',
                'team_id' => $teams->random()->id,
                'is_featured' => true,
                'latitude' => 51.284161,
                'longitude' => 0.192499,
            ],
            [
                'name' => 'Box Hill',
                'description' => 'A hill with stunning views of the Surrey countryside.',
                'category' => 'forest',
                'team_id' => $teams->random()->id,
                'is_featured' => false,
                'latitude' => 51.254186,
                'longitude' => -0.315083,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
