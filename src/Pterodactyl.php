<?php

namespace VizuaaLOG\Pterodactyl;

use GuzzleHttp\Client;
use VizuaaLOG\Pterodactyl\Managers\ServerManager;
use VizuaaLOG\Pterodactyl\Exceptions\InvalidApiKeyException;
use VizuaaLOG\Pterodactyl\Exceptions\InvalidBaseUriException;

class Pterodactyl {
    /**
     * API Key used for requests.
     * @var string
     */
    protected $api_key;

    /**
     * Base URI for API requests
     * @var string
     */
    protected $base_uri;

    /**
     * The GuzzelHTTP Client instance
     * @var \GuzzleHttp\Client
     */
    public $http;

    /**
     * Number of seconds before a request times out.
     * @var int
     */
    protected $timeout;

    /**
     * Instance of the server manager
     * @var \VizuaaLOG\Pterodactyl\Servers\Manager
     */
    public $servers;

    public function __construct($api_key, $base_uri, $client = null)
    {
        if(!$api_key) {
            throw new InvalidApiKeyException();
        }

        if(!$base_uri) {
            throw new InvalidBaseUriException();
        }

        $this->api_key = $api_key;
        $this->base_uri = $base_uri;

        $this->http = $client ?: new Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application_json'
            ],
            'timeout' => 30
        ]);

        $this->servers = new ServerManager($this);
    }

    /**
     * Set the API key to a new one.
     * @param string $api_key
     * @return void
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Set the base uri to a new value.
     * @param string $base_uri
     * @return void
     */
    public function setBaseUri($base_uri)
    {
        $this->base_uri = $base_uri;
    }

    /**
     * Set the client to a new GuzzleHTTP Client instance.
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->http = $client;
    }
}