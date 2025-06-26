<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class CreateChat
{
    public function __construct(
        public User $actor,
        public array $data,
        public string $ipAddress
    ) {}
}
