<?php

namespace Xelson\Chat\Api\Throttler;

use DateTime;
use Flarum\User\User;
use Flarum\Settings\SettingsRepositoryInterface;
use Xelson\Chat\Message;

class ChatMessage
{
    public function __construct(protected SettingsRepositoryInterface $settings) {}

    /**
     * @param User $actor
     * @return bool
     */
    public function __invoke($request): bool
    {
        $actor = $request->getAttribute('actor');

        if (!in_array($request->getAttribute('routeName'), ['discussions.create', 'posts.create'])) {
            return false;
        }

        $number = $this->settings->get('xelson-chat.settings.floodgate.number');
        $time = $this->settings->get('xelson-chat.settings.floodgate.time');

        if ($number <= 0) {
            return false;
        }

        $lastMessages = Message::where('created_at', '>=', new DateTime('-' . $time))
            ->where('user_id', $actor->id)
            ->orderBy('id', 'DESC')
            ->limit($number)
            ->get();

        if (count($lastMessages) <= $number) {
            return false;
        }

        return true;
    }
}
