<?php

namespace Xelson\Chat\Api\Controllers;

use Xelson\Chat\Api\Serializers\MessageSerializer;
use Xelson\Chat\Commands\FetchMessage;
use Illuminate\Support\Arr;
use Flarum\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FetchMessageController extends AbstractListController
{
    public $serializer = MessageSerializer::class;

    public $include = ['user', 'deleted_by', 'chat'];

    public function __construct(protected Dispatcher $bus) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $chatId = Arr::get($request->getQueryParams(), 'chat_id');
        $query = Arr::get($request->getQueryParams(), 'query', 0);

        return $this->bus->dispatch(
            new FetchMessage($query, $actor, $chatId)
        );
    }
}
