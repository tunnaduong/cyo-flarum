<?php

namespace Xelson\Chat\Event\Chat;

use Flarum\User\User;
use Xelson\Chat\Chat;

class Saved
{
    public function __construct(
        public Chat $chat,
        public User $actor,
        public array $data,
        public bool $created
    ) {}
}
