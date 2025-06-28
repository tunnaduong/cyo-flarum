<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class ReadChat
{
    public function __construct(public int $id, public User $actor, public string $readedAt) {}
}
