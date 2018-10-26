<?php

declare(strict_types=1);

namespace App\Service\Http\Users;

use App\Service\Http\{
    HttpClientAbstract, HttpClientInterface
};
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class GuzzleHttpClientService extends HttpClientAbstract implements HttpClientInterface
{
    /**
     * @param array $apiParameters
     */
    public function __construct(array $apiParameters)
    {
        $this->client = new Client([
            'base_uri' => $apiParameters['base_url'],
            'connect_timeout' => $apiParameters['timeout'],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * @param string $url
     * @param int    $page
     *
     * @return string
     */
    public function getJson(string $url, int $page = 1): string
    {
        try {
            $response = $this->client->get($url, ['query' => [ 'page' => $page ]]);

            $body = $response->getBody()->getContents();

        } catch (ConnectException $connectException) {
            $body = $connectException->getMessage();
        }

        return $body;
    }
}
