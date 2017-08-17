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
        call_user_func($this->listener, $event->getName(), $event->getSymfonyEvent(), $this->eventDispatcher);

        return $this;
    }
}