<?php declare(strict_types=1);

namespace CustomerGauge\XRay\Bags;

final class Http
{
    private string $method;

    private string $url;

    private string $client;

    private int $response;

    public function __construct(string $method, string $url, string $client, int $response)
    {
        $this->method = $method;
        $this->url = $url;
        $this->client = $client;
        $this->response = $response;
    }

    public function toArray(): array
    {
        return [
            'request' => [
                'method' => $this->method,
                'client_ip' => $this->client,
                'url' => $this->url,
            ],
            'response' => [
                'status' => $this->response,
            ]
        ];
    }
}
