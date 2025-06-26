<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class EditMessage
{
    public function __construct(public int $id, public User $actor, public array $data) {}
}
