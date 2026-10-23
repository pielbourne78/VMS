<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $category
 * @property string $title
 * @property array<array-key, mixed>|null $sanction_path
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereSanctionPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Offense whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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