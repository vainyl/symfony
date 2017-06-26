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

/**
 * Class BundleCompilerPass
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class BundleCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (false === ($container->hasDefinition('bundle.storage'))) {
            throw new MissingRequiredServiceException($container, 'bundle.storage');
        }

        $services = $container->findTaggedServiceIds('bundle');
        foreach ($services as $id => $tags) {
            $containerDefinition = $container->getDefinition('bundle.storage');
            $containerDefinition
                ->addMethodCall('addBundle', [new Reference($id)]);
        }

        return $this;
    }
}