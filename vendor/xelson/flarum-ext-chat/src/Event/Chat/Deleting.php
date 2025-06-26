<?php

namespace Xelson\Chat\Event\Chat;

use Flarum\User\User;
use Xelson\Chat\Chat;

class Deleting
{
    public function __construct(public Chat $chat, public User $actor) {}
}
