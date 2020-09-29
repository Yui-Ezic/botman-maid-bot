<?php

namespace App\Entities\Profanity;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $platform
 * @property int $platform_id
 * @property int $chat_id
 *
 * @method static Builder vk()
 *
 * @package App\Entities\Profanity
 * @mixin Eloquent
 */
class Subscriber extends Model
{
    public const PLATFORM_VK = 'vk';

    protected $table = 'profanity_subscribers';
    protected $fillable = ['platform', 'platform_id', 'chat_id'];

    public $timestamps = false;

    public static function subscribeVkUser(int $userId, int $chatId): self
    {
        return static::create([
            'platform' => self::PLATFORM_VK,
            'platform_id' => $userId,
            'chat_id' => $chatId
        ]);
    }

    public static function unsubscribeVkUser(int $userId, int $chatId)
    {
        return static::vk()
            ->where([
                ['platform_id', '=', $userId],
                ['chat_id', '=', $chatId],
            ])->delete();
    }

    public function scopeVk(Builder $query): Builder
    {
        return $query->where('platform', 'like', self::PLATFORM_VK);
    }

    public function isVkPlatform(): bool
    {
        return $this->platform === self::PLATFORM_VK;
    }

    public static function getSubscriber(int $userId, int $chatId, string $platform)
    {
        return static::where([
            ['platform_id', '=', $userId],
            ['chat_id', '=', $chatId],
            ['platform', 'like', $platform]
        ])->first();
    }
}
