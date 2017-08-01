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

namespace Vainyl\Symfony\Container\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Di\Factory\ContainerFactoryInterface;
use Vainyl\Di\Factory\Decorator\ContainerFactoryEnvironmentDecorator;
use Vainyl\Di\Factory\Decorator\ContainerFactoryExtensionDecorator;

/**
 * Class SymfonyContainerFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyContainerFactory extends AbstractIdentifiable implements ContainerFactoryInterface
{
    private $containerBuilder;

    /**
     * SymfonyContainerFactory constructor.
     *
     * @param ContainerBuilder $containerBuilder
     */
    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    /**
     * @inheritDoc
     */
    public function createContainer(EnvironmentInterface $environment)
    {
        return (new ContainerFactoryEnvironmentDecorator(
            new ContainerFactoryExtensionDecorator(
                new \Vainyl\Di\Factory\SymfonyContainerFactory($this->containerBuilder)
            )
        ))->createContainer($environment);
    }
}