<?php

namespace Xelson\Chat;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Database\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatUser extends AbstractModel
{
    protected $table = 'neonchat_chat_user';

    protected $dates = ['joined_at', 'readed_at', 'removed_at'];

    public static function build(
        int $chatId,
        int $userId,
        Carbon $joinedAt,
        ?Carbon $readedAt = null,
        int $role = 0,
        ?string $removedBy = null,
        ?Carbon $removedAt = null
    ): ChatUser {
        $model = new static();

        $model->chat_id = $chatId;
        $model->user_id = $userId;
        $model->role = $role;
        $model->joined_at = $joinedAt;
        $model->removed_by = $removedBy;
        $model->removed_at = $removedAt;
        $model->readed_at = $readedAt;

        return $model;
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
