<?php

namespace VizuaaLOG\Pterodactyl\Servers;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;

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

    public function update($server_id, $values)
    {
        try {
            $response = $this->http->request('PATCH', '/api/application/servers/' . $server_id . '/details', [
                'form_params' => $values
            ]);

            return $this->convert($response, false);
        } catch(ClientException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
        }
    }

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