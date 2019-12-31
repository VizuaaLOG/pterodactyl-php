<?php

namespace VizuaaLOG\Pterodactyl;

use GuzzleHttp\Client;
use VizuaaLOG\Pterodactyl\Managers\ServerManager;
use VizuaaLOG\Pterodactyl\Exceptions\InvalidApiKeyException;
use VizuaaLOG\Pterodactyl\Exceptions\InvalidBaseUriException;
use VizuaaLOG\Pterodactyl\Managers\UserManager;

class Pterodactyl
{
    /**
     * The GuzzleHTTP Client instance
     *
     * @var \GuzzleHttp\Client
     */
    public $http;

    /**
     * Instance of the server manager
     *
     * @var ServerManager
     */
    public $servers;

    /**
     * Instance of the user manager
     *
     * @var UserManager
     */
    public $users;

    /**
     * API Key used for requests.
     *
     * @var string
     */
    protected $api_key;

    /**
     * Base URI for API requests
     *
     * @var string
     */
    protected $base_uri;

    /**
     * Number of seconds before a request times out.
     *
     * @var int
     */
    protected $timeout;

    /** @var string */
    public $api_type;

    public function __construct($api_key, $base_uri, $type = 'application')
    {
        if (!$api_key) {
            throw new InvalidApiKeyException();
        }

        if (!$base_uri) {
            throw new InvalidBaseUriException();
        }

        $this->api_key = $api_key;
        $this->base_uri = $base_uri;

        $this->http = new Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);

        $this->api_type = $type;

        $this->servers = new ServerManager($this);
        $this->users = new UserManager($this);
    }

    /**
     * Set the API key to a new one.
     *
     * @param string $api_key
     *
     * @return void
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Set the base uri to a new value.
     *
     * @param string $base_uri
     *
     * @return void
     */
    public function setBaseUri($base_uri)
    {
        $this->base_uri = $base_uri;
    }

    /**
     * Set the client to a new GuzzleHTTP Client instance.
     *
     * @param \GuzzleHttp\Client $client
     *
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->http = $client;
    }
}