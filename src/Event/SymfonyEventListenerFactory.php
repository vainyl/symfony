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
use Vainyl\Event\EventHandlerInterface;

/**
 * Class SymfonyEventListenerFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyEventListenerFactory extends AbstractIdentifiable implements SymfonyEventListenerFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createListener(
        callable $listener,
        SymfonyEventDispatcherInterface $eventDispatcher
    ): EventHandlerInterface {
        $symfonyListener = $listener;
        if (is_array($listener) && isset($listener[0]) && $listener[0] instanceof \Closure) {
            $symfonyListener = $listener[0]();
        }

        return new SymfonyEventListenerAdapter($symfonyListener, $eventDispatcher);
    }
}