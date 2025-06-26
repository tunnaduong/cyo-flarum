<?php

namespace Xelson\Chat\Api\Serializers;

class ChatUserSerializer extends ChatSerializer
{
    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($chat)
    {
        $attributes = $chat->getAttributes();

        if($chat->created_at) {
            $attributes['created_at'] = $this->formatDate($chat->created_at);
        }

        if($chatUser = $chat->getChatUser($this->actor)) {
            $attributes['role'] = $chatUser->role;
            $attributes['joined_at'] = $this->formatDate($chatUser->joined_at);
            $attributes['readed_at'] = $this->formatDate($chatUser->readed_at);
            $attributes['removed_at'] = $this->formatDate($chatUser->removed_at);
            $attributes['removed_by'] = $chatUser->removed_by;
            $attributes['unreaded'] = $chat->unreadedCount($chatUser);
        }

        return $attributes;
    }
}
