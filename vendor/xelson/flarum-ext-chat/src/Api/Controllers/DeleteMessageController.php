<?php

namespace Xelson\Chat\Api\Controllers;

use Xelson\Chat\Api\Serializers\MessageSerializer;
use Xelson\Chat\Commands\DeleteMessage;
use Flarum\Api\Controller\AbstractShowController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class DeleteMessageController extends AbstractShowController
{
    public $serializer = MessageSerializer::class;

    public $include = ['user', 'deleted_by', 'chat'];

    public function __construct(protected Dispatcher $bus) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');

        return $this->bus->dispatch(
            new DeleteMessage($id, $actor)
        );
    }
}
