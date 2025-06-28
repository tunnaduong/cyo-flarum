<?php

namespace Xelson\Chat\Api\Controllers;

use Flarum\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

use Xelson\Chat\Api\Serializers\ChatSerializer;
use Xelson\Chat\Commands\CreateChat;

class CreateChatController extends AbstractCreateController
{
    public $serializer = ChatSerializer::class;

    public $include = ['creator', 'users', 'last_message'];

    public function __construct(protected Dispatcher $bus) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        return $this->bus->dispatch(
            new CreateChat($actor, $data, $ipAddress)
        );
    }
}
