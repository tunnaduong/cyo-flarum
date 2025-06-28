<?php

namespace Xelson\Chat;

class EventMessageChatAddRemoveUser extends AbstractEventMessage
{
    public string $id = 'chatAddRemoveUser';

    public function __construct(protected array $add, protected array $remove) {}

    public function getAttributes(): array
    {
        return [
            'add' => $this->add,
            'remove' => $this->remove,
        ];
    }
}
