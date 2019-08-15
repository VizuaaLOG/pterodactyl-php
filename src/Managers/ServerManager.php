<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use VizuaaLOG\Pterodactyl\Resources\Server;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;

class ServerManager extends Manager {
    /**
     * An instance of the http client.
     * @var \VizuaaLOG\Pterodactyl\Pterodactyl
     */
    protected $http;

    protected static $resource = Server::class;

    public function __construct($pterodactyl)
    {
        $this->pterodactyl = $pterodactyl;
        $this->http = $this->pterodactyl->http;
    }

    /**
     * Get all servers available.
     * @return array<\VizuaaLOG\Pterodactyl\Servers\Server>
     */
    public function all()
    {
        return $this->request('GET', '/api/application/servers');
    }

    /**
     * Get a single server object.
     * @param int $server_id
     * @return \VizuaaLOG\Pterodactyl\Servers\Server
     */
    public function get($server_id)
    {
        return $this->request('GET', '/api/application/servers/' . $server_id);
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
        return $this->request('POST', '/api/application/servers', $values);
    }

    /**
     * Update a server's configuration
     * 
     * @param int $server_id
     * @param array $values
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return array
     */
    public function update($server_id, $values)
    {
        return $this->request('PATCH', '/api/application/servers/' . $server_id . '/details', $values);
    }

    /**
     * Update a server's build configuration
     * 
     * @param int $server_id
     * @param array $values
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return array
     */
    public function updateBuild($server_id, $values)
    {
        try {
            $response = $this->http->request('PATCH', '/api/application/servers/' . $server_id . '/build', [
                'form_params' => $values
            ]);

            return $this->convert($response, false);
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        } catch(ServerException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }
    }

    /**
     * Update a server's startup configuration
     * 
     * @param int $server_id
     * @param array $values
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return array
     */
    public function updateStartup($server_id, $values)
    {
        try {
            $response = $this->http->request('PATCH', '/api/application/servers/' . $server_id . '/startup', [
                'form_params' => $values
            ]);

            return $this->convert($response, false);
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }
    }
    
    /**
     * Trigger a server rebuilt
     * 
     * @param int $server_id
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return bool
     */
    public function rebuild($server_id)
    {
        try {
            $response = $this->http->request('POST', '/api/application/servers/' . $server_id . '/rebuild');
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }

        return true;
    }

    /**
     * Suspend a server
     * 
     * @param int $server_id
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return bool
     */
    public function suspend($server_id)
    {
        try {
            $this->http->request('POST', '/api/application/servers/' . $server_id . '/suspend');
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }

        return true;
    }

    /**
     * Unsupsend a server
     * 
     * @param int $server_id
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return bool
     */
    public function unsuspend($server_id)
    {
        try {
            $this->http->request('POST', '/api/application/servers/' . $server_id . '/suspend');
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }

        return true;
    }

    /**
     * Trigger a reinstall of a server
     * 
     * @param int $server_id
     * 
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * 
     * @return bool
     */
    public function reinstall($server_id)
    {
        try {
            $this->http->request('POST', '/api/application/servers/' . $server_id . '/reinstall');
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }

        return true;
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
    protected function convert($json, $createServer = true)
    {
        if(is_array($json)) {
            $json = $json['attributes'];
        } else {
            $json = json_decode($json->getBody(), true)['attributes'];
        }

        if($createServer) {
            return new Server($json, $this->pterodactyl);
        }

        return $json;
    }
}