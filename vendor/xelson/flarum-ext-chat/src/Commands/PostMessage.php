<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class PostMessage
{
    public function __construct(
        public User $actor,
        public array $data,
        public string $ipAddress
    ) {}
}
