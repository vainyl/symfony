<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   Core
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\Symfony\Bundle\DependencyInjection;

use Vainyl\Core\Extension\AbstractExtension;

/**
 * Class AbstractBundleExtension
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
abstract class AbstractBundleExtension extends AbstractExtension
{
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
    public function getDirectory(): string
    {
        return dirname((new \ReflectionClass(get_class($this)))->getFileName()) . DIRECTORY_SEPARATOR . '..';
    }

    /**
     * @inheritDoc
     */
    public function getConfigDirectory(): string
    {
        if (false === $this->getEnvironment()->isDebugEnabled()) {
            return $this->getResourcesDirectory() . DIRECTORY_SEPARATOR . 'config';
        }

        return $this->getResourcesDirectory() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'debug';
    }
}
