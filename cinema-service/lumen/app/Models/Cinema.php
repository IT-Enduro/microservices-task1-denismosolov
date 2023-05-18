<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cinema extends Model
{
    protected $table = 'cinema';

    public $timestamps = false;

    protected $fillable = [
        'cinema_uid',
        'name',
        'address',
    ];

    protected $casts = [
        'cinema_uid' => 'string',
        'name' => 'string',
        'address' => 'string',
    ];

    /**
     * Get the comments for the blog post.
     */
    public function filmSessions(): HasMany
    {
        return $this->hasMany(FilmSession::class, 'cinema_id', 'id');
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        return [
            'cinemaUid' => $attributes['cinema_uid'],
            'name' => $attributes['name'],
            'address' => $attributes['address'],
        ];
    }
}
