<?php

namespace Xelson\Chat\Commands;

use Illuminate\Contracts\Events\Dispatcher;
use Xelson\Chat\ChatRepository;
use Xelson\Chat\Event\Message\Deleting;
use Xelson\Chat\MessageRepository;

class DeleteMessageHandler
{
    public function __construct(
        protected MessageRepository $message,
        protected ChatRepository $chat,
        protected Dispatcher $event
    ) {}

    public function handle(DeleteMessage $command)
    {
        $actor = $command->actor;

        $message = $this->message->findOrFail($command->id);

        $actor->assertPermission(!$message->type);

        $chat = $this->chat->findOrFail($message->chat_id, $actor);
        $chatUser = $chat->getChatUser($actor);

        $actor->assertPermission(
            $chatUser && $chatUser->role != 0
        );

        $this->event->dispatch(
            new Deleting($message, $actor)
        );

        $message->delete();
        $message->deleted_by = $actor->id;
        $message->deleted_forever = true;

        return $message;
    }
}
