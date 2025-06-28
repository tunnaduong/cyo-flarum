<?php

namespace Xelson\Chat\Commands;

use Xelson\Chat\ChatRepository;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Xelson\Chat\Event\Chat\Deleting;

class DeleteChatHandler
{
    public function __construct(
        protected ChatRepository $chat,
        protected BusDispatcher $bus,
        protected Dispatcher $event
    ) {}

    public function handle(DeleteChat $command)
    {
        $actor = $command->actor;

        $chat = $this->chat->findOrFail($command->id, $actor);

        $users = $chat->users()->get();

        $actor->assertPermission(
            ($actor->isAdmin() || $chat->creator_id == $actor->id) && (count($users) > 2 || $chat->type == 1)
        );

        $this->event->dispatch(
            new Deleting($chat, $actor)
        );

        $chat->users()->detach();
        $chat->delete();

        return $chat;
    }
}
