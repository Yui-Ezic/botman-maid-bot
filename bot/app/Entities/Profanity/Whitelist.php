<?php

namespace App\Entities\Profanity;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Whitelist words for profanity filter
 *
 * @property int $id
 * @property string $word
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin Eloquent
 */
class Whitelist extends Model
{
    public const TABLE_NAME = 'profanity_whitelist';

    protected $table = self::TABLE_NAME;

    protected $guarded = ['id'];

    /**
     * Returns array with whitelist words
     *
     * @return array
     */
    public static function getWhitelist(): array
    {
        return static::pluck('word')->toArray();
    }
}
