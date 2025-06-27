# Online Guests Widget

![License](https://img.shields.io/badge/license-MIT-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/deteh/flarum-ext-online-guests.svg)](https://packagist.org/packages/deteh/flarum-ext-online-guests) [![Total Downloads](https://img.shields.io/packagist/dt/deteh/flarum-ext-online-guests.svg)](https://packagist.org/packages/deteh/flarum-ext-online-guests)

A [Flarum](http://flarum.org) extension. Guests Online widget

## Installation

This extention will display the count of active users on the forum based on discussion views from [Discussion Views](https://github.com/MichaelBelgium/flarum-discussion-views)

This will also install [Forum Widgets Core](https://github.com/afrux/forum-widgets-core) as it relies on it.

Install with composer:

```sh
composer require deteh/flarum-ext-online-guests:"*"
```

## Updating

```sh
composer update deteh/flarum-ext-online-guests:"*"
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/deteh/online-guests)
- [GitHub](https://github.com/deteh/online-guests)
