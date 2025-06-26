<?php

namespace Xelson\Chat;

abstract class AbstractEventMessage implements EventMessageInterface
{
    public string $id = 'genericEvent';

    abstract public function getAttributes(): array;

    public function content(): string
    {
        $output = $this->getAttributes();
        $output['id'] = $this->id;

        return json_encode($output);
    }
}
