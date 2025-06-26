<?php

namespace Xelson\Chat\Commands;

use Carbon\Carbon;
use Xelson\Chat\ChatRepository;
use Xelson\Chat\Message;

class PostEventMessageHandler
{
    public function __construct(protected ChatRepository $chat) {}

    public function handle(PostEventMessage $command)
    {
        $actor = $command->actor;
        $event = $command->event;
        $ipAddress = $command->ipAddress;

        $chat = $this->chat->findOrFail($command->chatId, $actor);
        $content = $event->content();

        $message = Message::build(
            $content,
            $actor->id,
            Carbon::now(),
            $chat->id,
            $ipAddress,
            1
        );

        $message->save();

        return $message;
    }
}
