<?php declare(strict_types=1);

namespace CustomerGauge\XRay\Bags;

final class Segment
{
    private float $start;

    private ?float $end;

    private string $name;

    private array $annotations;

    private array $metadata;

    private ?Http $http = null;

    public function __construct(float $start, ?float $end, string $name, array $annotations, array $metadata)
    {
        $this->start = $start;
        $this->end = $end;
        $this->name = $name;
        $this->annotations = $annotations;
        $this->metadata = $metadata;
    }

    public static function from($start, string $name, array $annotations, array $metadata): self
    {
        return new self($start, microtime(true), $name, $annotations, $metadata);
    }

    public function finish(): self
    {
        $this->end = microtime(true);

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function start(): float
    {
        return $this->start;
    }

    public function end(): ?float
    {
        return $this->end;
    }

    public function annotations(): array
    {
        return $this->annotations;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function http(): ?Http
    {
        return $this->http;
    }

    public function withHttp(Http $http): self
    {
        $this->http = $http;

        return $this;
    }
}
