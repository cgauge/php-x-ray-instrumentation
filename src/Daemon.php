<?php

namespace CustomerGauge\XRay;

use CustomerGauge\XRay\Bags\Segment;
use CustomerGauge\XRay\Bags\Trace;

/**
 * @internal
 */
class Daemon
{
    private Trace $trace;

    private string $host;

    private int $port;

    private $socket;

    private int $counter = 1;

    public function __construct(Trace $trace, string $host = '127.0.0.1', int $port = 2000)
    {
        $this->trace = $trace;
        $this->host = $host;
        $this->port = $port;
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    }

    public static function fromEnvironmentVariable(Trace $trace): self
    {
        $address = explode(':', getenv('AWS_XRAY_DAEMON_ADDRESS'));

        return new self($trace, $address[0], (int)$address[1]);
    }

    public function __destruct()
    {
        socket_close($this->socket);
    }

    public function batch(array $segments, array $annotations): void
    {
        foreach ($segments as $segment) {
            $counter = $this->counter++;

            /** @var Segment $segment */

            $trace = [
                'name' => $segment->name(),
                'trace_id' => $this->trace->id(),
                'id' => str_pad($counter, 16, 0, STR_PAD_LEFT),
                'start_time' => $segment->start(),
                'end_time' => $segment->end(),
                'type' => 'subsegment',
                'parent_id' => $this->trace->parent(),
                'annotations' => $segment->annotations() + $annotations,
                'metadata' => $segment->metadata(),
            ];

            if ($http = $segment->http()) {
                $trace['http'] = $http->toArray();
            }

            $traceAsJson = PHP_EOL . json_encode($trace);

            $this->sendPacket('{"format": "json", "version": 1}' . $traceAsJson);
        }
    }

    private function sendPacket(string $packet)
    {
        socket_sendto($this->socket, $packet, strlen($packet), 0, $this->host, $this->port);
    }
}
