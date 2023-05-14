<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'film';

    public $timestamps = false;

    protected $fillable = [
        'film_uid',
        'name',
        'rating',
        'director',
        'producer',
        'genre',
    ];

    protected $casts = [
        'film_uid' => 'string',
        'name' => 'string',
        'rating' => 'float',
        'director' => 'string',
        'producer' => 'string',
        'genre' => 'string',
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        return [
            'filmUid' => $attributes['film_uid'],
            'name' => $attributes['name'],
            'rating' => $attributes['rating'],
            'director' => $attributes['director'],
            'producer' => $attributes['producer'],
            'genre' => $attributes['genre'],
        ];
    }
}
