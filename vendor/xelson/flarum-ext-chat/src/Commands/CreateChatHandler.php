<?php

namespace Xelson\Chat\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Xelson\Chat\Chat;
use Xelson\Chat\ChatUser;
use Xelson\Chat\ChatValidator;
use Xelson\Chat\ChatRepository;
use Xelson\Chat\EventMessageChatCreated;
use Xelson\Chat\Event\Chat\Saved;
use Xelson\Chat\Exceptions\ChatEditException;

class CreateChatHandler
{
    public function __construct(
        protected ChatValidator $validator,
        protected ChatRepository $chat,
        protected BusDispatcher $bus,
        protected Dispatcher $event
    ) {}

    public function handle(CreateChat $command)
    {
        $actor = $command->actor;
        $data = $command->data;
        $users = Arr::get($data, 'relationships.users.data', []);
        $attributes = Arr::get($data, 'attributes', []);
        $ipAddress = $command->ipAddress;

        $isChannel = intval($attributes['isChannel']);

        $actor->assertCan($isChannel ? 'xelson-chat.permissions.create.channel' : 'xelson-chat.permissions.create');

        $invited = [];

        foreach ($users as $key => $user) {
            if (array_key_exists($user['id'], $invited)) {
                throw new ChatEditException();
            }

            $invited[$user['id']] = true;
            if ($user['id'] == $actor->id) {
                array_splice($users, $key, 1);
            }
        }

        array_push($users, ['id' => $actor->id, 'type' => 'users']);

        if (!$isChannel && count($users) < 2) {
            throw new ChatEditException();
        }

        if (count($users) == 2) {
            $chats = $this->chat->query()
                ->where('type', 0)
                ->whereIn('id', ChatUser::select('chat_id')->where('user_id', $actor->id)->get()->toArray())
                ->with('users')
                ->get();

            foreach ($chats as $chat) {
                $chatUsers = $chat->users;

                if (
                    count($chatUsers) == 2 &&
                    ($chatUsers[0]->id == $users[0]['id'] || $chatUsers[0]->id == $users[1]['id']) &&
                    ($chatUsers[1]->id == $users[0]['id'] || $chatUsers[1]->id == $users[1]['id'])
                ) {
                    throw new ChatEditException();
                }
            }
        }

        $now = Carbon::now();
        $color = Arr::get($data, 'attributes.color', sprintf('#%06X', mt_rand(0x222222, 0xFFFF00)));
        $icon = Arr::get($data, 'attributes.icon', '');

        $chat = Chat::build(
            $attributes['title'],
            $color,
            $icon,
            $isChannel,
            $actor->id,
            Carbon::now()
        );

        $this->validator->assertValid($chat->getDirty());

        $chat->save();

        $userIds = [];

        if (!$isChannel) {
            foreach ($users as $user) {
                if ($user['id'] != $actor->id) {
                    $userIds[] = $user['id'];
                }
            }

            $pairs = [];
            foreach (array_merge($userIds, [$actor->id]) as $v) {
                $pairs[$v] = ['joined_at' => $now];
                if ($v == $actor->id) {
                    $pairs[$v]['role'] = 2;
                }
            }

            try {
                $chat->users()->sync($pairs);
            } catch (Exception $e) {
                $chat->delete();
                throw $e;
            }
        } else {
            try {
                $chat->users()->sync([$actor->id => ['role' => 2, 'joined_at' => $now]]);
            } catch (Exception $e) {
                $chat->delete();
                throw $e;
            }
        }

        $this->bus->dispatch(
            new PostEventMessage($chat->id, $actor, new EventMessageChatCreated($userIds), $ipAddress)
        );

        $this->event->dispatch(
            new Saved($chat, $actor, $data, true)
        );

        return $chat;
    }
}
