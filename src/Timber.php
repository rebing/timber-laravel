<?php

namespace Rebing\Timber;

use GuzzleHttp\Client;
use Rebing\Timber\Exceptions\TimberException;

/**
 * Communicates with the Timber logger API (https://api-docs.timber.io/)
 *
 * Class Timber
 * @package Rebing\Timber
 */
class Timber
{
    const SERVER_URI = 'https://logs.timber.io/';

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    private $requestUri;
    private $apiKey;

    /**
     * Timber constructor.
     * @throws TimberException
     */
    public function __construct()
    {
        $this->requestUri = self::SERVER_URI;
        $this->apiKey = config('timber.api_key');

        if (is_null($this->apiKey)) {
            throw new TimberException('API key not set!');
        }
    }

    protected function doRequest(string $method, string $endpoint, array $options = [])
    {
        $client = new Client([
            'base_uri' => $this->requestUri,
            'headers'  => [
                'Authorization' => 'Basic ' . base64_encode($this->apiKey),
            ],
        ]);

        $res = $client->request($method, $endpoint, $options);

        return $res->getBody()->getContents();
    }
}