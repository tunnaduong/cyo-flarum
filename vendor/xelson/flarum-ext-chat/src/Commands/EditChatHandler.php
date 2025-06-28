<?php

namespace Xelson\Chat\Commands;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Xelson\Chat\ChatValidator;
use Xelson\Chat\ChatRepository;
use Xelson\Chat\EventMessageChatEdited;
use Xelson\Chat\EventMessageChatAddRemoveUser;
use Xelson\Chat\Exceptions\ChatEditException;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Xelson\Chat\Event\Chat\Saved;

class EditChatHandler
{
    public function __construct(
        protected ChatValidator $validator,
        protected ChatRepository $chats,
        protected BusDispatcher $bus,
        protected Dispatcher $events
    ) {}

    public function handle(EditChat $command)
    {
        $actor = $command->actor;
        $data = $command->data;
        $attributes = Arr::get($data, 'attributes', []);
        $ipAddress = $command->ipAddress;

        $chat = $this->chats->findOrFail($command->id, $actor);
        $allUsers = $chat->users()->get();
        $allIds = [];
        $currentIds = [];
        $users = [];

        foreach ($allUsers as $user) {
            $allIds[] = $user->id;
            $users[$user->id] = $user;
            if (!$user->pivot->removed_at) {
                $currentIds[] = $user->id;
            }
        }

        $editableColumns = ['title', 'icon', 'color'];

        $eventsList = [];
        $attrsChanged = false;

        $actor->assertPermission(
            in_array($actor->id, $allIds)
        );

        $localUser = $users[$actor->id];

        $actor->assertPermission(
            !$localUser->pivot->removed_at || $localUser->pivot->removed_by == $actor->id
        );

        $now = Carbon::now();
        $isCreator = $actor->id == $chat->creator_id || (!$chat->creator_id && $actor->isAdmin());
        $isPM = count($allUsers) <= 2 && $chat->type == 0;
        $isChannel = $chat->type == 1;

        foreach ($editableColumns as $column) {
            if (Arr::get($data, 'attributes.' . $column, 0) && $chat[$column] != $attributes[$column]) {
                $actor->assertPermission(
                    $isChannel || !$isPM
                );

                $actor->assertPermission(
                    $localUser->pivot->role || $isCreator
                );

                $message = $this->bus->dispatch(
                    new PostEventMessage($chat->id, $actor, new EventMessageChatEdited($column, $chat[$column], $attributes[$column]), $ipAddress)
                );
                $eventsList[] = $message->id;
                $chat[$column] = $attributes[$column];

                $attrsChanged = true;
            }
        }

        $added = Arr::get($data, 'attributes.users.added', 0);
        $removed = Arr::get($data, 'attributes.users.removed', 0);

        if ($added || $removed) {
            $addedIds = [];
            $removedIds = [];

            if ($added) {
                foreach ($added as $user) {
                    $addedIds[] = $user['id'];
                }
            }

            if ($removed) {
                foreach ($removed as $user) {
                    $removedIds[] = $user['id'];
                }
            }

            $addedIds = array_unique($addedIds);
            $removedIds = array_unique($removedIds);

            if (count(array_intersect($addedIds, $removedIds))) {
                throw new ChatEditException('Trying to add and remove users in the same time');
            }

            if (count($addedIds) && count(array_intersect($addedIds, $currentIds))) {
                throw new ChatEditException(sprintf('Cannot add new users: one of them already in chat (%s and %s)', json_encode($addedIds), json_encode($currentIds)));
            }

            if (count($removedIds) && !count(array_intersect($removedIds, $currentIds))) {
                throw new ChatEditException('Cannot kick users: one of them already kicked');
            }

            if ($isPM && (count($addedIds) > 1 || count($removedIds) > 1 || (count($addedIds) && $addedIds[0] != $actor->id) || (count($removedIds) && $removedIds[0] != $actor->id))) {
                throw new ChatEditException('Invalid user array for PM chat room');
            }

            if (count($addedIds) || count($removedIds)) {
                $addedPairs = [];
                $removedPairs = [];

                foreach ($addedIds as $v) {
                    $addedPairs[$v] = ['removed_at' => null, 'removed_by' => null];
                }

                foreach ($removedIds as $v) {
                    $actor->assertPermission(
                        $v == $actor->id || $users[$v]->pivot->role < $localUser->pivot->role || $isCreator
                    );
                    $removedPairs[$v] = ['removed_at' => $now, 'removed_by' => $actor->id];
                }

                $chat->users()->syncWithoutDetaching($addedPairs + $removedPairs);

                if (!$isChannel) {
                    $message = $this->bus->dispatch(
                        new PostEventMessage($chat->id, $actor, new EventMessageChatAddRemoveUser($addedIds, $removedIds), $ipAddress)
                    );
                    $eventsList[] = $message->id;
                }
            }
        }

        $rolesUpdatedFor = [];

        $edited = Arr::get($data, 'attributes.users.edited', 0);
        if ($edited) {
            $actor->assertPermission(
                !$isPM && $isCreator
            );

            $syncUsers = [];

            foreach ($edited as $user) {
                $id = $user['id'];
                $role = $user['role'];

                if (array_search($id, $allIds) === false) {
                    continue;
                }

                if ($id == $actor->id) {
                    throw new ChatEditException('Сannot set a role for yourself');
                }

                if (!in_array($role, [0, 1, 2])) {
                    throw new ChatEditException('Unacceptable role');
                }

                $syncUsers[$id] = ['role' => $role];
                if ($role != $users[$id]->pivot->role) {
                    $rolesUpdatedFor[] = $id;
                }
            }

            $chat->users()->syncWithoutDetaching($syncUsers);
        }

        if ($attrsChanged) {
            $this->validator->assertValid($chat->getDirty());
            $chat->save();
        }

        $chat->eventmsg_range = $eventsList;
        $chat->roles_updated_for = $rolesUpdatedFor;

        $this->events->dispatch(
            new Saved($chat, $actor, $data, false)
        );

        return $chat;
    }
}
