<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    
    protected $fillable = [
        'name',
        'description',
        'category',
        'team_id',
        'latitude',
        'longitude',
        'is_featured'
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Team::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
