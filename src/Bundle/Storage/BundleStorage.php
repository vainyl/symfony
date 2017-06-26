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

namespace Vainyl\Symfony\Bundle\Storage;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Vainyl\Core\Storage\Decorator\AbstractStorageDecorator;

/**
 * Class BundleStorage
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class BundleStorage extends AbstractStorageDecorator
{
    /**
     * @param BundleInterface $bundle
     *
     * @return BundleStorage
     */
    public function addBundle(BundleInterface $bundle) : BundleStorage
    {
        $this->offsetSet($bundle->getName(), $bundle);

        return $this;
    }
}