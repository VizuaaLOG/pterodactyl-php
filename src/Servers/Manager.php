<?php

namespace VizuaaLOG\Pterodactyl\Servers;

use GuzzleHttp\Exception\ClientException;
use VizuaaLOG\Pterodactyl\Exceptions\RequestException;

use function GuzzleHttp\json_decode;

class Manager {
    /**
     * An instance of the http client.
     * @var \VizuaaLOG\Pterodactyl\Pterodactyl
     */
    protected $http;

    public function __construct($pterodactyl)
    {
        $this->pterodactyl = $pterodactyl;
        $this->http = $pterodactyl->http;
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
     * Create a new server
     * @param array $values
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return \VizuaaLOG\Pterodactyl\Servers\Server
     */
    public function create($values)
    {
        try {
            $response = $this->http->request('POST', '/api/application/servers', [
                'form_params' => $values
            ]);
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }

        return $this->convert($response);
    }

    /**
     * Delete a server
     * @var int|string $server_id
     * @var bool $force
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return bool
     */
    public function delete($server_id, $force = false)
    {
        $endpoint = '/api/application/servers/' . $server_id;
        
        if($force) {
            $endpoint .= '/force';
        }

        try {
            $response = $this->http->request('DELETE', $endpoint);

            return true;
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }
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

        return new Server($json, $this->pterodactyl);
    }
}