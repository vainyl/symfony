<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   Symfony-bridge
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\Symfony\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\Core\Storage\StorageInterface;
use Vainyl\Event\EventHandlerInterface;

/**
 * Class SymfonyEventListenerFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyEventListenerFactory extends AbstractIdentifiable implements SymfonyEventListenerFactoryInterface
{
    private $storage;

    /**
     * SymfonyEventListenerFactory constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function createListener($listener, SymfonyEventDispatcherInterface $eventDispatcher): EventHandlerInterface
    {
        $symfonyListener = $listener;
        if (is_array($symfonyListener) && isset($symfonyListener[0]) && $symfonyListener[0] instanceof \Closure) {
            $symfonyListener[0] = $listener[0]();
        }

        $key = $this->mapCallableToStorageKey($symfonyListener);
        if (false === $this->storage->offsetExists($key)) {
            $this->storage->offsetSet(
                $key,
                new SymfonyEventListenerAdapter($symfonyListener, $eventDispatcher)
            );
        }

        return $this->storage->offsetGet($key);
    }

    /**
     * @param callable $listener
     *
     * @return mixed
     */
    private function mapCallableToStorageKey($listener)
    {
        $key = $listener;
        if (is_array($key) && is_object($key[0] ?? null)) {
            $key[0] = spl_object_hash($key[0]);
        }

        return $key;
    }
}
