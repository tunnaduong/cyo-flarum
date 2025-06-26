<?php

namespace Xelson\Chat\Commands;

use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Xelson\Chat\ChatRepository;
use Xelson\Chat\Event\Message\Saved;
use Xelson\Chat\Message;
use Xelson\Chat\MessageValidator;

class PostMessageHandler
{
    public function __construct(
        protected MessageValidator $validator,
        protected ChatRepository $chats,
        protected Dispatcher $events
    ) {}

    public function handle(PostMessage $command)
    {
        $actor = $command->actor;
        $attributes = $command->data['attributes'];
        $ipAddress = $command->ipAddress;

        $content = $attributes['message'];

        $chat = $this->chats->findOrFail($attributes['chat_id'], $actor);

        $actor->assertCan('xelson-chat.permissions.chat');

        $chatUser = $chat->getChatUser($actor);

        $actor->assertPermission($chatUser && !$chatUser->removed_at);

        $message = Message::build(
            $content,
            $actor->id,
            Carbon::now(),
            $chat->id,
            $ipAddress
        );

        $this->validator->assertValid($message->getDirty());

        $message->save();

        $chat->users()->updateExistingPivot($actor->id, ['readed_at' => Carbon::now()]);

        $this->events->dispatch(
            new Saved($message, $actor, $command->data, true)
        );

        return $message;
    }
}
