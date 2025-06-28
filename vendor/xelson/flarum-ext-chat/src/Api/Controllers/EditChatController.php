<?php

namespace Xelson\Chat\Api\Controllers;

use Xelson\Chat\Api\Serializers\ChatUserSerializer;
use Flarum\Api\Controller\AbstractShowController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

use Xelson\Chat\Commands\EditChat;
use Xelson\Chat\Commands\ReadChat;

class EditChatController extends AbstractShowController
{
    public $serializer = ChatUserSerializer::class;

    public $include = ['creator', 'users', 'last_message'];

    public function __construct(protected Dispatcher $bus) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        if ($readedAt = Arr::get($data, 'attributes.actions.reading')) {
            return $this->bus->dispatch(
                new ReadChat($id, $actor, $readedAt)
            );
        }

        return $this->bus->dispatch(
            new EditChat($id, $actor, $data, $ipAddress)
        );
    }
}
