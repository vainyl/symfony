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

namespace Vainyl\Symfony\Application;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Vainyl\Core\Application\EnvironmentInterface;

/**
 * Interface SymfonyEnvironmentInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
interface SymfonyEnvironmentInterface extends EnvironmentInterface
{
    /**
     * @return BundleInterface[]
     */
    public function getBundles(): array;

    /**
     * @return string
     */
    public function getLogsDirectory(): string;
}