<?php

namespace Xelson\Chat;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Database\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends AbstractModel
{
    protected $table = 'neonchat_messages';

    protected $dates = ['created_at', 'edited_at'];

    public static function build(
        string $message,
        int $userId,
        Carbon $createdAt,
        int $chatId = 1,
        ?string $ipAddress = null,
        int $type = 0,
        bool $isReaded = false,
        ?Carbon $editedAt = null,
        ?Carbon $deletedBy = null
    ): Message {
        $msg = new static();

        $msg->message = $message;
        $msg->user_id = $userId;
        $msg->created_at = $createdAt;
        $msg->edited_at = $editedAt;
        $msg->deleted_by = $deletedBy;
        $msg->chat_id = $chatId;
        $msg->type = $type;
        $msg->is_readed = $isReaded;
        $msg->ip_address = $ipAddress;

        return $msg;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deleted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
