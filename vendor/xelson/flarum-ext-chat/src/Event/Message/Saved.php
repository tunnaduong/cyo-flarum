<?php

namespace Xelson\Chat\Event\Message;

use Flarum\User\User;
use Xelson\Chat\Message;

class Saved
{
    public function __construct(
        public Message $message,
        public User $actor,
        public array $data,
        public bool $created
    ) {}
}
