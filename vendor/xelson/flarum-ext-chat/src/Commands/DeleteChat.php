<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class DeleteChat
{
    public function __construct(public int $id, public User $actor) {}
}
