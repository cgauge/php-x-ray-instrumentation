<?php declare(strict_types=1);

namespace CustomerGauge\XRay;

use CustomerGauge\XRay\Bags\Trace;

final class Client
{
    private string $trace;

    private array $annotations;

    private Collector $collector;

    public function __construct(string $trace, array $annotations, Collector $collector)
    {
        $this->trace = $trace;
        $this->annotations = $annotations;
        $this->collector = $collector;
    }


    public function send()
    {
        $trace = Trace::make($this->trace);

        $daemon = Daemon::fromEnvironmentVariable($trace);

        $daemon->batch($this->collector->segments(), $this->annotations);
    }
}
