<?php declare(strict_types=1);

namespace CustomerGauge\XRay\Bags;

final class Trace
{
    private string $id;

    private string $parent;

    private function __construct(string $id, string $parent)
    {
        $this->id = $id;
        $this->parent = $parent;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function parent(): string
    {
        return $this->parent;
    }

    public static function make(string $trace): self
    {
        //Root=1-5e94b5fd-9559209b71bdcf3d6d2067a3;
        //Parent=f4af593caaca5260;
        //Sampled=1
        $data = explode(';', $trace);

        // @TODO: throw exception when trace is invalid.

        $trace = explode('=', $data[0]);

        $parent = explode('=', $data[1]);

        return new self($trace[1], $parent[1]);
    }
}
