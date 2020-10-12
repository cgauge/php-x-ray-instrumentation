<?php declare(strict_types=1);

namespace CustomerGauge\XRay;

use CustomerGauge\XRay\Bags\Segment;

final class Collector
{
    private $segments = [];

    public function collect(Segment $segment): void
    {
        $this->segments[] = $segment;
    }

    public function segments(): array
    {
        return $this->segments;
    }
}
