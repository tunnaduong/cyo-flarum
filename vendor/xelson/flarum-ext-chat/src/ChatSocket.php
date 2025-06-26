<?php

namespace Xelson\Chat;

class ChatSocket extends PusherWrapper
{
    protected $channel = 'neonchat.events';

    public function sendChatEvent($chatId, $eventId, $options): void
    {
        if (!$this->pusher()) {
            return;
        }

        $chat = Chat::findOrFail($chatId);

        $attributes = [
            'event' => [
                'id' => $eventId,
                'chat_id' => $chatId
            ],
            'response' => $options
        ];

        if ($chat) {
            $chat->type ? $this->sendPublic($attributes) : $this->sendPrivate($chat->id, $attributes);
        }
    }

    public function sendPublic($attributes): void
    {
        $this->pusher()->trigger('public', $this->channel, $attributes);
    }

    public function sendPrivate($chatId, $attributes): void
    {
        $chatUsers = ChatUser::where('chat_id', $chatId)
            ->whereNull('removed_at')
            ->pluck('user_id')
            ->all();

        foreach ($chatUsers as $user_id) {
            $this->pusher()->trigger('private-user' . $user_id, $this->channel, $attributes);
        }
    }
}
