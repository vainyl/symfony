<?php

namespace Vainyl\Symfony\Kernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Vainyl\Symfony\Application\SymfonyEnvironmentInterface;

/**
 * Class AbstractSymfonyKernel
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
abstract class AbstractSymfonyKernel extends Kernel
{
    private $appEnvironment;

    use MicroKernelTrait;

    /**
     * AbstractSymfonyKernel constructor.
     *
     * @param SymfonyEnvironmentInterface $appEnvironment
     */
    public function __construct(SymfonyEnvironmentInterface $appEnvironment)
    {
        $this->appEnvironment = $appEnvironment;
        parent::__construct($appEnvironment->getName(), $appEnvironment->isDebugEnabled());
    }

    /**
     * @return SymfonyEnvironmentInterface
     */
    public function getAppEnvironment(): SymfonyEnvironmentInterface
    {
        return $this->appEnvironment;
    }

    /**
     * @inheritDoc
     */
    public function registerBundles()
    {
        return $this->appEnvironment->getBundles();
    }

    /**
     * @inheritDoc
     */
    protected function getContainerBuilder()
    {
        return (new \Vainyl\Symfony\Container\Factory\SymfonyContainerFactory(parent::getContainerBuilder()))->createContainer($this->appEnvironment);
    }

    /**
     * @inheritDoc
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        foreach ($this->getAppEnvironment()->getExtensions() as $extension) {
            $loader->load($extension->getConfigDirectory() . DIRECTORY_SEPARATOR . 'services.yml');
        }

    }

    /**
     * @inheritDoc
     */
    protected function initializeContainer()
    {
        parent::initializeContainer();

        $this->getContainer()->set('app.environment', $this->appEnvironment);
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir()
    {
        return $this->appEnvironment->getCacheDirectory();
    }

    /**
     * @inheritDoc
     */
    public function getLogDir()
    {
        return $this->appEnvironment->getLogsDirectory();
    }
}