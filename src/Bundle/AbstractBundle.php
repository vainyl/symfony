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

use Symfony\Component\HttpKernel\Bundle\Bundle as AbstractSymfonyBundle;
use Vainyl\Core\NameableInterface;
use Vainyl\Symfony\Application\SymfonyEnvironmentInterface;

/**
 * Class AbstractBundle
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class AbstractBundle extends AbstractSymfonyBundle implements NameableInterface
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
     * @return SymfonyEnvironmentInterface
     */
    public function getEnvironment(): SymfonyEnvironmentInterface
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
     * @return string
     */
    public function getDirectory(): string
    {
        return dirname((new \ReflectionClass(get_class($this)))->getFileName())
               . DIRECTORY_SEPARATOR . '..'
               . DIRECTORY_SEPARATOR . '..';
    }

    /**
     * @return string
     */
    public function getConfigDirectory(): string
    {
        if ($this->getEnvironment()->isDebugEnabled()) {
            return $this->getDirectory() . DIRECTORY_SEPARATOR . 'config';
        }

        return $this->getDirectory() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'debug';
    }
}