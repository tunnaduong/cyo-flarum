<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;
use Xelson\Chat\EventMessageInterface;

class PostEventMessage
{
    public function __construct(
        public int $chatId,
        public User $actor,
        public EventMessageInterface $event,
        public string $ipAddress
    ) {}
}
