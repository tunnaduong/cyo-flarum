<?php

namespace Xelson\Chat\Commands;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Xelson\Chat\ChatRepository;

class ReadChatHandler
{
    public function __construct(protected BusDispatcher $bus, protected ChatRepository $chat) {}

    public function handle(ReadChat $command)
    {
        $actor = $command->actor;

        $chat = $this->chat->findOrFail($command->id, $actor);

        $chatUser = $chat->getChatUser($actor);

        $actor->assertPermission($chatUser);

        $time = new Carbon($command->readedAt);

        if ($chatUser->removed_at && $time > $chatUser->removed_at) {
            $time = $chatUser->removed_at;
        }

        $chat->users()->updateExistingPivot($actor->id, ['readed_at' => $time]);

        return $chat;
    }
}
