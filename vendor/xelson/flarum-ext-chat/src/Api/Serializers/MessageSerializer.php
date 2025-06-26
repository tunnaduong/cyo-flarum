<?php

namespace Xelson\Chat\Api\Serializers;

use Flarum\User\User;
use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Settings\SettingsRepositoryInterface;
use Xelson\Chat\ChatSocket;

class MessageSerializer extends AbstractSerializer
{
    protected $type = 'chatmessages';

    /**
     * @var User
     */
    protected $actor;

    public function __construct(
        protected SettingsRepositoryInterface $settings,
        protected ChatSocket $socket
    ) {}

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($message)
    {
        $attributes = $message->getAttributes(); // danger thing, i have to remove this line in all models
        unset($attributes['ip_address']);

        $attributes['created_at'] = $this->formatDate($message->created_at);

        if($attributes['edited_at']) {
            $attributes['edited_at'] = $this->formatDate($message->edited_at);
        }

        if($this->settings->get('xelson-chat.settings.display.censor') && !$this->actor->id) {
            $attributes['message'] = str_repeat("*", strlen($attributes['message']));
            $attributes['is_censored'] = true;
        }

        return $attributes;
    }

    /**
     * @return \Tobscure\JsonApi\Relationship
     */
    public function user($message)
    {
        return $this->hasOne($message, BasicUserSerializer::class);
    }

    /**
     * @return \Tobscure\JsonApi\Relationship
     */
    public function deleted_by($message)
    {
        return $this->hasOne($message, BasicUserSerializer::class);
    }

    /**
     * @return \Tobscure\JsonApi\Relationship
     */
    public function chat($message)
    {
        return $this->hasOne($message, ChatSerializer::class);
    }
}
