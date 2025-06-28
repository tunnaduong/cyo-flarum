<?php

namespace Xelson\Chat;

use Flarum\Database\AbstractModel;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class ChatRepository
{
    public function query(): Builder
    {
        return Chat::query();
    }

    public function queryVisible(User $actor): Builder
    {
        $query = $this->query();

        $query->where(function ($query) use ($actor) {
            $query
                ->where('type', 1)
                ->orWhereIn('id', ChatUser::select('chat_id')->where('user_id', $actor->id)->get()->toArray());
        });

        return $query;
    }

    public function findOrFail(int $id, User $actor): AbstractModel
    {
        return $this->queryVisible($actor)->findOrFail($id);
    }
}
