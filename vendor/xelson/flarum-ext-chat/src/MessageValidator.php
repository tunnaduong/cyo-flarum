<?php

namespace Xelson\Chat;

use Flarum\Foundation\AbstractValidator;
use Flarum\Settings\SettingsRepositoryInterface;

class MessageValidator extends AbstractValidator
{
    protected function getRules(): array
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $max = $settings->get('xelson-chat.settings.charlimit');

        return [
            'message' => ['required', 'string', 'max:' . $max],
        ];
    }
}
