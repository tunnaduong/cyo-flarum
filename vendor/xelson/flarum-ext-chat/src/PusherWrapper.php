<?php

namespace Xelson\Chat;

use Flarum\Settings\SettingsRepositoryInterface;
use Pusher\Pusher;
use Pusher as PusherLegacy;

class PusherWrapper
{
    /**
     * @var Pusher|PusherLegacy|false
     */
    protected $pusher;

    public function __construct(protected SettingsRepositoryInterface $settings) {}

    private function buildPusher(): Pusher|PusherLegacy|false
    {
        if (class_exists(Pusher::class) && app()->bound(Pusher::class)) {
            return resolve(Pusher::class);
        } elseif (class_exists(PusherLegacy::class)) {
            $settings = resolve('flarum.settings');

            $options = [];

            if ($cluster = $settings->get('flarum-pusher.app_cluster')) {
                $options['cluster'] = $cluster;
            }

            return new PusherLegacy(
                $settings->get('flarum-pusher.app_key'),
                $settings->get('flarum-pusher.app_secret'),
                $settings->get('flarum-pusher.app_id'),
                $options
            );
        } else {
            return false;
        }
    }

    public function pusher(): Pusher|PusherLegacy|false
    {
        if (is_null($this->pusher)) {
            $this->pusher = $this->buildPusher();
        }

        return $this->pusher;
    }
}
