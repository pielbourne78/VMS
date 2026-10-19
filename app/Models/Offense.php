<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offense extends Model
{
    protected $fillable = ['code', 'category', 'title', 'sanction_path', 'is_active'];

    protected function casts(): array
    {
        return [
            'sanction_path' => 'array',
            'is_active' => 'boolean',
        ];
    }
}