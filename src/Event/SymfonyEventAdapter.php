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

namespace Vainyl\Symfony\Event;

use Symfony\Component\EventDispatcher\Event;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\Event\EventInterface;

/**
 * Class SymfonyEventAdapter
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class SymfonyEventAdapter extends AbstractIdentifiable implements EventInterface
{
    private $name;

    private $symfonyEvent;

    /**
     * SymfonyEventAdapter constructor.
     *
     * @param string     $name
     * @param Event|null $symfonyEvent
     */
    public function __construct(string $name, Event $symfonyEvent = null)
    {
        $this->name = $name;
        $this->symfonyEvent = $symfonyEvent;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Event
     */
    public function getSymfonyEvent(): ?Event
    {
        return $this->symfonyEvent;
    }
}