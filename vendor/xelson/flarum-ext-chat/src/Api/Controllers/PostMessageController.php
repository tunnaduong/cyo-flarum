<?php

namespace Xelson\Chat\Api\Controllers;

use Xelson\Chat\Api\Serializers\MessageSerializer;
use Xelson\Chat\Commands\PostMessage;
use Flarum\Api\Controller\AbstractShowController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class PostMessageController extends AbstractShowController
{
    public $serializer = MessageSerializer::class;

    public $include = ['user', 'deleted_by', 'chat'];

    public function __construct(protected Dispatcher $bus) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        return $this->bus->dispatch(
            new PostMessage($actor, $data, $ipAddress)
        );
    }
}
