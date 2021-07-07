<?php declare(strict_types = 1);

namespace SandwaveIo\EppClient\Epp\ConnectionDriver\Support;

/**
 * This class just sleeps in its most basic form. However when $incrementSleepInterval is enabled, it increases its
 * sleeptime every time the sleep() method is called on the instance. At first this happens by the amount of
 * $intervalIncrementorPrimary, after the interval has grown over the $intervalIncrementorLimit, the
 * $intervalIncrementorSecondary is used.
 * When the sleepInterval reaches the $maxSleepInterval, no further increments are made.
 */
final class SleepTimer
{
    /** @var int */
    private $sleepInterval;

    /** @var bool */
    private $incrementSleepInterval;

    /** @var int */
    private $maxSleepInterval;

    /** @var int */
    private $intervalIncrementorLimit;

    /** @var int */
    private $intervalIncrementorPrimary;

    /** @var int */
    private $intervalIncrementorSecondary;

    public function __construct(
        int $initialSleepInterval = 100,
        bool $incrementSleepInterval = true,
        int $maxSleepInterval = 100000,
        int $intervalIncrementorLimit = 10000,
        int $intervalIncrementorPrimary = 1000,
        int $intervalIncrementorSecondary = 100
    ) {
        $this->maxSleepInterval = $maxSleepInterval;
        $this->incrementSleepInterval = $incrementSleepInterval;
        $this->intervalIncrementorLimit = $intervalIncrementorLimit;
        $this->intervalIncrementorPrimary = $intervalIncrementorPrimary;
        $this->intervalIncrementorSecondary = $intervalIncrementorSecondary;

        $this->sleepInterval = $initialSleepInterval;
    }

    public function sleep(): void
    {
        usleep($this->sleepInterval);

        if (! $this->incrementSleepInterval) {
            return;
        }

        if ($this->sleepInterval >= $this->maxSleepInterval) {
            return;
        }

        $increment = ($this->sleepInterval < $this->intervalIncrementorLimit)
            ? $this->intervalIncrementorPrimary
            : $this->intervalIncrementorSecondary;

        $this->sleepInterval += $increment;
    }
}
