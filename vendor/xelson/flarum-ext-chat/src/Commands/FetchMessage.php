<?php

namespace Xelson\Chat\Commands;

use Flarum\User\User;

class FetchMessage
{
    public function __construct(public mixed $query, public User $actor, public int $id) {}
}
