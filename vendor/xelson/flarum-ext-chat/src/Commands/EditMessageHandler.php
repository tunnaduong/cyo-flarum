<?php

namespace Xelson\Chat\Commands;

use Carbon\Carbon;
use Xelson\Chat\ChatRepository;
use Xelson\Chat\MessageRepository;
use Xelson\Chat\MessageValidator;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Xelson\Chat\Event\Message\Saved;

class EditMessageHandler
{
    public function __construct(
        protected MessageRepository $message,
        protected MessageValidator $validator,
        protected ChatRepository $chat,
        protected Dispatcher $event
    ) {}

    public function handle(EditMessage $command)
    {
        $actor = $command->actor;
        $data = $command->data;
        $attributes = Arr::get($data, 'attributes', []);
        $actions = $attributes['actions'];

        $message = $this->message->findOrFail($command->id);

        $actor->assertPermission(
            !$message->type
        );

        $chat = $this->chat->findOrFail($message->chat_id, $actor);
        $chatUser = $chat->getChatUser($actor);

        if (isset($actions['msg'])) {
            $actor->assertCan('xelson-chat.permissions.edit');
            $actor->assertPermission($actor->id == $message->user_id);
            $actor->assertPermission($message->message != $actions['msg']);

            $message->message = $actions['msg'];
            $message->edited_at = Carbon::now();

            $this->validator->assertValid($message->getDirty());

            $message->save();
        } elseif (isset($actions['hide'])) {
            $actor->assertCan('xelson-chat.permissions.delete');

            if ($actions['hide']) {
                if ($message->user_id != $actor->id) {
                    $actor->assertPermission(
                        $chatUser && $chatUser->role != 0
                    );
                }
                $message->deleted_by = $actor->id;
            } else {
                if ($message->deleted_by != $actor->id) {
                    $actor->assertPermission(
                        $chatUser && $chatUser->role != 0
                    );
                }
                $message->deleted_by = null;
            }

            $message->save();
            $actions['invoker'] = $actor->id;
        }

        $message->actions = $actions;

        $this->event->dispatch(
            new Saved($message, $actor, $data, false)
        );

        return $message;
    }
}
