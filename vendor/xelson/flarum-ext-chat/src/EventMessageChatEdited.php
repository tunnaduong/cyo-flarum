<?php

namespace Xelson\Chat;

class EventMessageChatEdited extends AbstractEventMessage
{
    public string $id = 'chatEdited';

    public function __construct(
        protected string $column,
        protected mixed $old,
        protected mixed $new
    ) {}

    public function getAttributes(): array
    {
        return [
            'column' => $this->column,
            'old' => $this->old,
            'new' => $this->new
        ];
    }
}
