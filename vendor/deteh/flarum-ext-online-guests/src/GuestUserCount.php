<?php

/*
 * This file is part of deteh/online-guests.
 *
 * Copyright (c) deteh.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace deteh\OnlineGuests;

use Afrux\ForumWidgets\SafeCacheRepositoryAdapter;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Settings\SettingsRepositoryInterface;
use Michaelbelgium\Discussionviews\Models\DiscussionView;

class GuestUserCount
{
    protected $cache;
    protected $settings;
    protected $onlineDurationMinutes = 5;
    protected $cacheDurationSeconds = 60;

    public function __construct(SafeCacheRepositoryAdapter $cache, SettingsRepositoryInterface $settings)
    {
        $this->cache = $cache;
        $this->settings = $settings;
    }

    public function __invoke(ForumSerializer $serializer): array
    {
        if ($serializer->getActor()->hasPermission('viewOnlineGuests')) {
            $this->onlineDurationMinutes = (int) $this->settings->get('deteh-online-guests.online-duration');
            $this->cacheDurationSeconds = (int) $this->settings->get('deteh-online-guests.cache-duration');

            return [
                'onlineGuests' => $this->cache->remember('deteh-online-guests', $this->cacheDurationSeconds, function () {
                    return $this->getGuestCount();
                }),
            ];
        }

        return [];
    }

    protected function getGuestCount(): int
    {
        $result = DiscussionView::whereRaw('visited_at >= (NOW() - INTERVAL ' . $this->onlineDurationMinutes . ' MINUTE)')->groupBy('ip')->get();

        if ($result->count() < 1) {
            return 1;
        }

        return $result->count();
    }
}
