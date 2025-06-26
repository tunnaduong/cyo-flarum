<?php

namespace Xelson\Chat;

use Flarum\Foundation\AbstractValidator;
use Illuminate\Validation\Rule;

class ChatValidator extends AbstractValidator
{
    protected function getRules(): array
    {
        return [
            'title' => ['max:100'],
            'color' => ['max:20'],
            'icon' => ['max:100'],
            'type' => ['required', Rule::in([0, 1])],
        ];
    }
}
