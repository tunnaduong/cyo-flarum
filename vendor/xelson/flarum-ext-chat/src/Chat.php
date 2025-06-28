<?php

namespace Xelson\Chat;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Database\AbstractModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends AbstractModel
{
    protected $table = 'neonchat_chats';

    protected $dates = ['created_at'];

    public static function build(
        string $title,
        int $color,
        string $icon,
        int $type,
        int $creator_id = null,
        Carbon $createdAt = null
    ) {
        $chat = new static();

        $chat->title = $title;
        $chat->color = $color;
        $chat->icon = $icon;
        $chat->type = $type;
        $chat->creator_id = $creator_id;
        $chat->created_at = $createdAt;

        return $chat;
    }

    public function unreadedCount($chatUser)
    {
        $start = $chatUser->readed_at;

        if ($start == null) {
            $start = 0;
        }

        $query = $this->messages()->where('created_at', '>', $start);

        if ($chatUser->removed_at) {
            $query->where('created_at', '<=', $chatUser->removed_at);
        }

        return $query->count();
    }

    public function getChatUser(User $user): ?ChatUser
    {
        $chatUser = ChatUser::where('chat_id', $this->id)->where('user_id', $user->id)->first();

        if (!$chatUser && $user->id && $this->type == 1) {
            $now = Carbon::now();
            $this->users()->attach($user->id, ['readed_at' => $now]);
            $chatUser = ChatUser::build($this->id, $user->id, $now, $now);
        }

        return $chatUser;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'neonchat_chat_user')
            ->withPivot('joined_at', 'removed_by', 'role', 'readed_at', 'removed_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function last_message(): HasOne
    {
        return $this->hasOne(Message::class)->orderBy('id', 'desc')->whereNull('deleted_by');
    }

    public function first_message(): HasOne
    {
        return $this->hasOne(Message::class)->orderBy('id', 'asc')->whereNull('deleted_by');
    }
}
