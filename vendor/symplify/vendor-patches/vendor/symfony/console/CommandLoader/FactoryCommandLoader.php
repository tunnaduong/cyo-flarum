<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace VendorPatches202502\Symfony\Component\Console\CommandLoader;

use VendorPatches202502\Symfony\Component\Console\Command\Command;
use VendorPatches202502\Symfony\Component\Console\Exception\CommandNotFoundException;
/**
 * A simple command loader using factories to instantiate commands lazily.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class FactoryCommandLoader implements CommandLoaderInterface
{
    /**
     * @var mixed[]
     */
    private $factories;
    /**
     * @param callable[] $factories Indexed by command names
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }
    public function has(string $name) : bool
    {
        return isset($this->factories[$name]);
    }
    public function get(string $name) : Command
    {
        if (!isset($this->factories[$name])) {
            throw new CommandNotFoundException(\sprintf('Command "%s" does not exist.', $name));
        }
        $factory = $this->factories[$name];
        return $factory();
    }
    public function getNames() : array
    {
        return \array_keys($this->factories);
    }
}
