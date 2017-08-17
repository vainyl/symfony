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

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vainyl\Core\Exception\MissingRequiredServiceException;

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
        if (false === $container->hasDefinition('event.dispatcher.symfony')) {
            throw new MissingRequiredServiceException($container, 'event.dispatcher.symfony');
        }

        if ($container->hasDefinition('event_dispatcher')) {
            $container->removeDefinition('event_dispatcher');
            $container->setAlias('event_dispatcher', new Alias('event.dispatcher.symfony'));
        }

        if ($container->hasDefinition('debug.event_dispatcher')) {
            $container->removeDefinition('event_dispatcher');
            $container->setAlias('debug.event_dispatcher', new Alias('event.dispatcher.symfony'));
        }
    }
}