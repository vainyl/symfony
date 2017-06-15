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

namespace Vainyl\Symfony\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Vainyl\Core\Application\AbstractApplication;
use Vainyl\Http\Application\HttpApplicationInterface;

/**
 * Class SymfonyApplication
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyApplication extends AbstractApplication implements HttpApplicationInterface
{
    private $httpFactory;

    private $appKernel;

    /**
     * SymfonyApplication constructor.
     *
     * @param DiactorosFactory $responseFactory
     * @param KernelInterface  $kernel
     */
    public function __construct(DiactorosFactory $responseFactory, KernelInterface $kernel)
    {
        $this->httpFactory = $responseFactory;
        $this->appKernel = $kernel;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'symfony';
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->httpFactory->createResponse($this->appKernel->handle(Request::createFromGlobals()));
    }
}