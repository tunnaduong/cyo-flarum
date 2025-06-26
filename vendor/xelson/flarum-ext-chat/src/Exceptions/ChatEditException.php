<?php

namespace Xelson\Chat\Exceptions;

use Exception;
use Flarum\Foundation\KnownError;

class ChatEditException extends Exception implements KnownError
{
    public function getType(): string
    {
        return 'invalid_parameter';
    }
}
