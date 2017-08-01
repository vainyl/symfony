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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpKernel\Kernel;
use Vainyl\Core\Application\AbstractApplication;
use Vainyl\Http\Application\HttpApplicationInterface;

/**
 * Class SymfonyApplication
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyApplication extends AbstractApplication implements HttpApplicationInterface
{
    private $psrFactory;

    private $symfonyFactory;

    private $appKernel;

    /**
     * SymfonyApplication constructor.
     *
     * @param HttpMessageFactoryInterface    $psrFactory
     * @param HttpFoundationFactoryInterface $symfonyFactory
     * @param Kernel                         $kernel
     */
    public function __construct(
        HttpMessageFactoryInterface $psrFactory,
        HttpFoundationFactoryInterface $symfonyFactory,
        Kernel $kernel
    ) {
        $this->psrFactory = $psrFactory;
        $this->symfonyFactory = $symfonyFactory;
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
        $symfonyRequest = $this->symfonyFactory->createRequest($request);
        $symfonyResponse = $this->appKernel->handle($symfonyRequest);
        $this->appKernel->terminate($symfonyRequest, $symfonyResponse);

        return $this->psrFactory->createResponse($symfonyResponse);
    }
}