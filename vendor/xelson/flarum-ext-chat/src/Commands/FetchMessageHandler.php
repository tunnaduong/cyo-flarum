<?php

namespace Xelson\Chat\Commands;

use Xelson\Chat\ChatRepository;
use Xelson\Chat\MessageRepository;

class FetchMessageHandler
{
    public function __construct(
        protected MessageRepository $message,
        protected ChatRepository $chat
    ) {}

    public function handle(FetchMessage $command)
    {
        $actor = $command->actor;
        $query = $command->query;

        $chat = $this->chat->findOrFail($command->id, $actor);

        if(is_array($query)) {
            $messages = $this->message->queryVisible($chat, $actor)->whereIn('id', $query)->get();
        } else {
            $messages = $this->message->fetch($query, $actor, $chat);
        }

        return $messages;
    }
}
