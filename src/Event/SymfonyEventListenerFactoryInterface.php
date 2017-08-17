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
use Vainyl\Core\IdentifiableInterface;
use Vainyl\Event\EventHandlerInterface;

/**
 * Interface SymfonyEventListenerFactoryInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
interface SymfonyEventListenerFactoryInterface extends IdentifiableInterface
{
    /**
     * @param callable                        $listener
     * @param SymfonyEventDispatcherInterface $eventDispatcher
     *
     * @return EventHandlerInterface
     */
    public function createListener(
        $listener,
        SymfonyEventDispatcherInterface $eventDispatcher
    ): EventHandlerInterface;
}