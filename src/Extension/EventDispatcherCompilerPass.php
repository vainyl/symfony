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

namespace Vainyl\Symfony\Extension;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Vainyl\Core\Exception\MissingRequiredServiceException;
use Vainyl\Symfony\Event\SymfonyEventListenerAdapter;

/**
 * Class EventDispatcherCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class EventDispatcherCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        foreach (['event.dispatcher', 'event.handler.storage', 'event.handler.storage'] as $requiredService) {
            if (false === $container->hasDefinition($requiredService)) {
                throw new MissingRequiredServiceException($container, $requiredService);
            }
        }

        if ($container->hasDefinition('event_dispatcher')) {
            $container
                ->getDefinition('event_dispatcher')
                ->setClass(SymfonyEventListenerAdapter::class)
                ->setArguments(
                    [
                        new Reference('event.dispatcher'),
                        new Reference('event.handler.storage'),
                        new Reference('event.listener.factory.symfony'),
                    ]
                );
        }
        if ($container->hasDefinition('debug.event_dispatcher')) {
            $container
                ->getDefinition('debug.event_dispatcher')
                ->setClass(SymfonyEventListenerAdapter::class)
                ->setArguments(
                    [
                        new Reference('event.dispatcher'),
                        new Reference('event.handler.storage'),
                        new Reference('event.listener.factory.symfony'),
                    ]
                );
        }
    }
}