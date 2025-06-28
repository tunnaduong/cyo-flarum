<?php

namespace Xelson\Chat\Api\Controllers;

use Xelson\Chat\Commands\DeleteChat;
use Flarum\Api\Controller\AbstractShowController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

use Xelson\Chat\Api\Serializers\ChatSerializer;

class DeleteChatController extends AbstractShowController
{
    public $serializer = ChatSerializer::class;

    public $include = ['creator', 'users', 'last_message'];

    public function __construct(protected Dispatcher $bus) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');

        return $this->bus->dispatch(
            new DeleteChat($id, $actor)
        );
    }
}
