<?php

declare(strict_types=1);

namespace App\Core\Domain\Bus\Event;

use App\Core\Domain\Utils\Trait\DateUtil;

/**
 * Abstract class for domain events in a domain-driven design context.
 *
 * Domain events represent something that happened in the domain that
 * the application wants to respond to.
 * They are immutable once constructed, ensuring the event's integrity.
 */
abstract readonly class DomainEvent
{
    use DateUtil;

    private EventId $eventId;
    private string $occurredOn;

    /**
     * Constructs a DomainEvent.
     */
    public function __construct(
        private string $aggregateId,
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        $this->eventId = $eventId ?? Ulid::generate();
        $this->occurredOn = $occurredOn ?? self::dateToString(new \DateTimeImmutable());
    }

    /**
     * Returns the name of the event.
     *
     * @return string the name of the event
     */
    abstract public static function eventName(): string;

    /**
     * Converts the DomainEvent to an array of primitive types.
     *
     * @return array the array representation of the event
     */
    abstract public function toPrimitives(): array;

    /**
     * Gets the aggregate identifier related to the event.
     *
     * @return string the identifier of the aggregate
     */
    final public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * Gets the unique identifier of the event.
     *
     * @return string the unique identifier of the event
     */
    final public function eventId(): string
    {
        return $this->eventId;
    }

    /**
     * Gets the time when the event occurred.
     *
     * @return string the occurrence time of the event
     */
    final public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}