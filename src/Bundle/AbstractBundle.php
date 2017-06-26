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

namespace Vainyl\Symfony\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle as AbstractSymfonyBundle;
use Vainyl\Core\Application\EnvironmentInterface;
use Vainyl\Core\Extension\ExtensionInterface;
use Vainyl\Symfony\Application\SymfonyEnvironmentInterface;

/**
 * Class AbstractBundle
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class AbstractBundle extends AbstractSymfonyBundle implements ExtensionInterface
{
    private $environment;

    /**
     * AbstractBundle constructor.
     *
     * @param SymfonyEnvironmentInterface $environment
     */
    public function __construct(SymfonyEnvironmentInterface $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environment;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return spl_object_hash($this);
    }

    /**
     * Creates the bundle's container extension.
     *
     * @return ExtensionInterface|null
     */
    protected function createContainerExtension()
    {
        if (false === class_exists($class = $this->getContainerExtensionClass())) {
            return null;
        }

        return new $class($this->environment);
    }

    /**
     * @return string
     */
    public function getResourcesDirectory(): string
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . 'Resources';
    }

    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $definition = (new \Symfony\Component\DependencyInjection\Definition())
            ->setClass(get_class($this))
            ->addArgument(new \Symfony\Component\DependencyInjection\Reference('app.environment'))
            ->addTag('bundle');

        $container->setDefinition(sprintf('bundle.%s', $this->getName()), $definition);
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return dirname((new \ReflectionClass(get_class($this)))->getFileName());
    }

    /**
     * @inheritDoc
     */
    public function getCompilerPasses(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getConfigDirectory(): string
    {
        return $this->getResourcesDirectory() . DIRECTORY_SEPARATOR . 'config';
    }
}