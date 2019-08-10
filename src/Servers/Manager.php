<?php

namespace VizuaaLOG\Pterodactyl\Servers;

use function GuzzleHttp\json_decode;

class Manager {
    /**
     * An instance of the http client.
     * @var \VizuaaLOG\Pterodactyl\Pterodactyl
     */
    protected $http;

    public function __construct($http)
    {
        $this->http = $http;
    }

    /**
     * Get all servers available.
     * @return array<\VizuaaLOG\Pterodactyl\Servers\Server>
     */
    public function all()
    {
        $response = $this->http->request('GET', '/api/application/servers');
        $serversJson = json_decode($response->getBody(), true);
        $servers = [];

        foreach($serversJson['data'] as $serverJson) {
            $servers[] = $this->convert($serverJson);
        }

        return $servers;
    }

    /**
     * Get a single server object.
     * @param int $server_id
     * @return \VizuaaLOG\Pterodactyl\Servers\Server
     */
    public function get($server_id)
    {
        try {
            $response = $this->http->request('GET', '/api/application/servers/' . $server_id);
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            return false;
        }
        
        return $this->convert($response);
    }

    /**
     * Get a single server object using it's external ID.
     * @param int $server_id
     * @return \VizuaaLOG\Pterodactyl\Servers\Server
     */
    public function getByExternalId($server_id)
    {
        try {
            $response = $this->http->request('GET', '/api/application/servers/external' . $server_id);
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            return false;
        }
        
        return $this->convert($response);
    }

    public function create($values)
    {

    }

    /**
     * Convert a server JSON object into a Server class.
     * @param array $response
     * @return \VizuaaLOG\Pterodactyl\Servers\Server
     */
    protected function convert($json)
    {
        if(is_array($json)) {
            $json = $json['attributes'];
        } else {
            $json = json_decode($json->getBody(), true)['attributes'];
        }

        $server = new Server();

        $server->id = (int) $json['id'];
        $server->external_id = $json['external_id'];
        $server->uuid = $json['uuid'];
        $server->identifier = $json['identifier'];
        $server->name = $json['name'];
        $server->description = $json['description'];
        $server->suspended = (bool) $json['suspended'];

        $server->limits = [
            'memory' => (int) $json['limits']['memory'],
            'swap' => (int) $json['limits']['swap'],
            'disk' => (int) $json['limits']['disk'],
            'io' => (int) $json['limits']['io'],
            'cpu' => (int) $json['limits']['cpu'],
        ];

        $server->feature_limits = [
            'databases' => (int) $json['feature_limits']['databases'],
            'allocations' => (int) $json['feature_limits']['allocations'],
        ];

        $server->user = (int) $json['user'];
        $server->node = (int) $json['node'];
        $server->allocation = (int) $json['allocation'];
        $server->nest = (int) $json['nest'];
        $server->egg = (int) $json['egg'];
        $server->pack = (int) $json['pack'];

        $server->container = [
            'startup_command' => $json['container']['startup_command'],
            'image' => $json['container']['image'],
            'installed' => (bool) $json['container']['installed'],
            'environment' => [
                'SERVER_JARFILE' => $json['container']['environment']['SERVER_JARFILE'] ?? '',
                'VANILLA_VERSION' => $json['container']['environment']['VANILLA_VERSION'] ?? '',
                'STARTUP' => $json['container']['environment']['STARTUP'] ?? '',
                'P_SERVER_LOCATION' => $json['container']['environment']['P_SERVER_LOCATION'] ?? '',
                'P_SERVER_UUID' => $json['container']['environment']['P_SERVER_UUID'] ?? '',
            ]
        ];

        $server->updated_at = $json['updated_at'];
        $server->created_at = $json['updated_at'];

        return $server;
    }
}