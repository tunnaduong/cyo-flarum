<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class EditChat
{
    public function __construct(
        public int $id,
        public User $actor,
        public array $data,
        public string $ipAddress
    ) {}
}
