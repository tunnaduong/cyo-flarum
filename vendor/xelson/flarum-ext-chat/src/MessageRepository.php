<?php

namespace Xelson\Chat;

use Carbon\Carbon;
use Flarum\Database\AbstractModel;
use Flarum\Database\Eloquent\Collection;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class MessageRepository
{
    public int $messagesPerFetch = 20;

    public function query(): Builder
    {
        return Message::query();
    }

    public function findOrFail(int $id): AbstractModel
    {
        return $this->query()->findOrFail($id);
    }

    public function queryVisible(Chat $chat, User $actor): Builder
    {
        $query = $this->query();
        $user = $chat->getChatUser($actor);

        if(!$user || !$user->role) {
            $query->where(function (Builder $query) use ($actor) {
                $query
                    ->whereNull('deleted_by')
                    ->orWhere('deleted_by', $actor->id);
            });
        }

        return $query;
    }

    public function fetch(string $time, User $actor, Chat $chat): Collection
    {
        $user = $chat->getChatUser($actor);

        $top = $this->queryVisible($chat, $actor)->where('chat_id', $chat->id);

        if($user && $user->removed_at) {
            $top->where('created_at', '<=', $user->removed_at);
        }

        $top->where('created_at', '>=', new Carbon($time))->limit($this->messagesPerFetch + 1);

        $bottom = $this->queryVisible($chat, $actor)->where('chat_id', $chat->id);

        if($user && $user->removed_at) {
            $bottom->where('created_at', '<=', $user->removed_at);
        }

        $bottom->where('created_at', '<', new Carbon($time))->orderBy('id', 'desc')->limit($this->messagesPerFetch);

        $messages = $top->union($bottom);

        return $messages->get();
    }
}
