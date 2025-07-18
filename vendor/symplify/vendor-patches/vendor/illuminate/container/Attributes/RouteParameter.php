<?php

namespace VendorPatches202502\Illuminate\Container\Attributes;

use Attribute;
use VendorPatches202502\Illuminate\Contracts\Container\Container;
use VendorPatches202502\Illuminate\Contracts\Container\ContextualAttribute;
#[Attribute(Attribute::TARGET_PARAMETER)]
class RouteParameter implements ContextualAttribute
{
    /**
     * @var string
     */
    public $parameter;
    /**
     * Create a new class instance.
     */
    public function __construct(string $parameter)
    {
        $this->parameter = $parameter;
    }
    /**
     * Resolve the route parameter.
     *
     * @param  self  $attribute
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return mixed
     */
    public static function resolve(self $attribute, Container $container)
    {
        return $container->make('request')->route($attribute->parameter);
    }
}
