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

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vainyl\Event\EventDispatcherInterface;
use Vainyl\Event\Storage\EventHandlerStorageInterface;

/**
 * Class SymfonyEventDispatcherAdapter
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyEventDispatcherAdapter implements SymfonyEventDispatcherInterface
{
    private $eventDispatcher;

    private $handlerStorage;

    private $listenerFactory;

    /**
     * SymfonyEventDispatcherAdapter constructor.
     *
     * @param EventDispatcherInterface             $eventDispatcher
     * @param EventHandlerStorageInterface         $handlerStorage
     * @param SymfonyEventListenerFactoryInterface $listenerFactory
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EventHandlerStorageInterface $handlerStorage,
        SymfonyEventListenerFactoryInterface $listenerFactory
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->handlerStorage = $handlerStorage;
        $this->listenerFactory = $listenerFactory;
    }

    /**
     * @inheritDoc
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->handlerStorage->addHandler(
            $eventName,
            $this->listenerFactory->createListener($listener, $this),
            $priority
        );
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener($eventName, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $this->addListener($eventName, [$subscriber, $params[0]], isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener($eventName, [$subscriber, $listener[0]], isset($listener[1]) ? $listener[1] : 0);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function dispatch($eventName, Event $event = null)
    {
        if ($event->isPropagationStopped()) {
            return;
        }

        $this->eventDispatcher->dispatch(new SymfonyEventAdapter($eventName, $event));
    }

    /**
     * @inheritDoc
     */
    public function getListenerPriority($eventName, $listener)
    {
        return $this->handlerStorage->getListenerPriority(
            $eventName,
            $this->listenerFactory->createListener($listener, $this)
        );
    }

    /**
     * @inheritDoc
     */
    public function getListeners($eventName = null)
    {
        if (null === $eventName) {
            return $this->handlerStorage;
        }

        return $this->handlerStorage->getHandlers((string)$eventName);
    }

    /**
     * @inheritDoc
     */
    public function hasListeners($eventName = null)
    {
        if (null === $eventName) {
            return true;
        }

        return $this->handlerStorage->hasListeners((string)$eventName);
    }

    /**
     * @inheritDoc
     */
    public function removeListener($eventName, $listener)
    {
        $this->handlerStorage->removeHandler($eventName, $this->listenerFactory->createListener($listener, $this));
    }

    /**
     * @inheritDoc
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListener($eventName, [$subscriber, $listener[0]]);
                }
            } else {
                $this->removeListener($eventName, [$subscriber, is_string($params) ? $params : $params[0]]);
            }
        }
    }
}