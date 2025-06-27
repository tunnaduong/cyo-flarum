<?php

namespace TheTurk\Flamoji\Api\Controllers;

use Flarum\Api\Controller\AbstractCreateController;
use TheTurk\Flamoji\Api\Serializers\EmojiSerializer;
use TheTurk\Flamoji\Commands\ImportEmoji;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ImportEmojiController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = EmojiSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(
            new ImportEmoji(Arr::get($request->getParsedBody(), 'data', []))
        );
    }
}
