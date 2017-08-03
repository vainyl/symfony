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

use Vainyl\Core\AbstractArray;

/**
 * Class AbstractSymfonyEnvironment
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
abstract class AbstractSymfonyEnvironment extends AbstractArray implements SymfonyEnvironmentInterface
{
    private $appDir;

    private $env;

    private $isDebug;

    private $isCacheable;

    /**
     * Environment constructor.
     *
     * @param string $appDir
     * @param string $env
     * @param bool   $isDebug
     * @param bool   $isCacheable
     */
    public function __construct(string $appDir, string $env, bool $isDebug, bool $isCacheable)
    {
        $this->appDir = $appDir;
        $this->env = $env;
        $this->isDebug = $isDebug;
        $this->isCacheable = $isCacheable;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->env;
    }

    /**
     * @inheritDoc
     */
    public function isDebugEnabled(): bool
    {
        return $this->isDebug;
    }

    /**
     * @inheritDoc
     */
    public function isCachingEnabled(): bool
    {
        return $this->isCacheable;
    }

    /**
     * @inheritDoc
     */
    public function getApplicationDirectory(): string
    {
        return $this->appDir;
    }

    /**
     * @inheritDoc
     */
    public function getConfigDirectory(): string
    {
        return $this->getApplicationDirectory() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * @inheritDoc
     */
    public function getDebugDirectory(): string
    {
        return $this->getConfigDirectory() . DIRECTORY_SEPARATOR . 'debug';
    }

    /**
     * @inheritDoc
     */
    public function getCacheDirectory(): string
    {
        return sprintf(
            '%s%s%s%s%s',
            $this->getApplicationDirectory(),
            DIRECTORY_SEPARATOR,
            'var',
            DIRECTORY_SEPARATOR,
            'cache'
        );
    }

    /**
     * @inheritDoc
     */
    public function getLogsDirectory(): string
    {
        return sprintf(
            '%s%s%s%s%s',
            $this->getApplicationDirectory(),
            DIRECTORY_SEPARATOR,
            'var',
            DIRECTORY_SEPARATOR,
            'logs'
        );
    }

    /**
     * @inheritDoc
     */
    public function getContainerConfig(): string
    {
        return 'di.yml';
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'app' => [
                'dir' => $this->appDir,
                'env' => $this->env,
            ],
            'config' => ['dir' => $this->getConfigDirectory()],
            'debug' => ['dir' => $this->getDebugDirectory(), 'enabled' => $this->isDebug],
            'cache' => ['dir' => $this->getCacheDirectory(), 'enabled' => $this->isCacheable],
        ];
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}