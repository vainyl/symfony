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

use Vainyl\Core\Extension\AbstractFrameworkExtension;

/**
 * Class SymfonyExtension
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyExtension extends AbstractFrameworkExtension
{
    /**
     * @inheritDoc
     */
    public function getCompilerPasses(): array
    {
        return [new BundleCompilerPass()];
    }
}