<?php

namespace Xelson\Chat\Event\Message;

use Flarum\User\User;
use Xelson\Chat\Message;

class Deleting
{
    public function __construct(public Message $message, public User $actor) {}
}
