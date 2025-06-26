<?php

namespace Xelson\Chat;

class EventMessageChatCreated extends AbstractEventMessage
{
    public string $id = 'chatCreated';

    public function __construct(protected array $users) {}

    public function getAttributes(): array
    {
        return [
            'users' => $this->users,
        ];
    }
}
