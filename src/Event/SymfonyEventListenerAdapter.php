<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   Symfony-Bridge
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\Symfony\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event as SymfonyEvent;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\Event\EventHandlerInterface;
use Vainyl\Event\EventInterface;

/**
 * Class SymfonyEventListenerAdapter
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyEventListenerAdapter extends AbstractIdentifiable implements EventHandlerInterface
{
    private $listener;

    private $eventDispatcher;

    /**
     * SymfonyEventListenerAdapter constructor.
     *
     * @param callable                        $listener
     * @param SymfonyEventDispatcherInterface $eventDispatcher
     */
    public function __construct($listener, SymfonyEventDispatcherInterface $eventDispatcher)
    {
        $this->listener = $listener;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param SymfonyEventAdapter $event
     *
     * @return EventHandlerInterface
     */
    public function handle(EventInterface $event): EventHandlerInterface
    {
        // Ad-hoc solution. Vainyl\Event\EventDispatcher can't handle propagation stopped case
        if ($event->getSymfonyEvent()->isPropagationStopped()) {
            return $this;
        }

        call_user_func($this->listener, $event->getSymfonyEvent(), $event->getName(), $this->eventDispatcher);

        return $this;
    }

    /**
     * Implementation for symfony event dispatcher.
     *
     * @param SymfonyEvent                    $event
     * @param string                          $eventName
     * @param SymfonyEventDispatcherInterface $dispatcher
     *
     * @return SymfonyEventListenerAdapter
     */
    public function __invoke(SymfonyEvent $event, string $eventName, SymfonyEventDispatcherInterface $dispatcher)
    {
        call_user_func($this->listener, $event, $eventName, $dispatcher);

        return $this;
    }
}

