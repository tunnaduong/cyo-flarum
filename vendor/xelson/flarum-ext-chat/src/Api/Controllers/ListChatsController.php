<?php

namespace Xelson\Chat\Api\Controllers;

use Xelson\Chat\Api\Serializers\ChatUserSerializer;
use Xelson\Chat\ChatRepository;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Flarum\Api\Controller\AbstractListController;

class ListChatsController extends AbstractListController
{
    public $include = [
        'creator',
        'users',
        'last_message',
        'last_message.user',
        'first_message'
    ];

    public function __construct(
        protected Dispatcher $bus,
        protected ChatRepository $chats,
        public $serializer = ChatUserSerializer::class,
    ) {}

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $include = $this->extractInclude($request);

        return $this->chats->queryVisible($actor)->get()->load($include);
    }
}
