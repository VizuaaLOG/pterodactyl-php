<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use VizuaaLOG\Pterodactyl\Resources\Server;

class ServerManager extends Manager
{
    /**
     * @var string The resource this manager uses.
     */
    protected static $resource = Server::class;

    /**
     * Get all servers available.
     *
     * @return array<\VizuaaLOG\Pterodactyl\Resources\Server>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/servers');
    }

    /**
     * Get a single server object.
     *
     * @param int   $server_id
     * @param array $includes
     *
     * @return array|\VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function get($server_id, $includes = [])
    {
        return $this->request('GET', '/api/application/servers/' . $server_id, null, true, $includes);
    }

    /**
     * Create a new server
     *
     * @param array $values
     *
     * @return array|\VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function create($values)
    {
        return $this->request('POST', '/api/application/servers', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int   $server_id
     * @param array $values
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function update($server_id, $values)
    {
        return $this->request('PATCH', '/api/application/servers/' . $server_id . '/details', $values);
    }

    /**
     * Update a server's build configuration
     *
     * @param int   $server_id
     * @param array $values
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function updateBuild($server_id, $values)
    {
        return $this->request('PATCH', '/api/application/servers/' . $server_id . '/build', $values, false);
    }

    /**
     * Update a server's startup configuration
     *
     * @param int   $server_id
     * @param array $values
     *
     * @return array
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function updateStartup($server_id, $values)
    {
        return $this->request('PATCH', '/api/application/servers/' . $server_id . '/startup', $values, false);
    }

    /**
     * Trigger a server rebuilt
     *
     * @param int $server_id
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function rebuild($server_id)
    {
        return $this->request('POST', '/api/application/servers/' . $server_id . '/rebuild');
    }

    /**
     * Suspend a server
     *
     * @param int $server_id
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function suspend($server_id)
    {
        return $this->request('POST', '/api/application/servers/' . $server_id . '/suspend');
    }

    /**
     * Unsuspend a server
     *
     * @param int $server_id
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function unsuspend($server_id)
    {
        return $this->request('POST', '/api/application/servers/' . $server_id . '/unsuspend');
    }

    /**
     * Trigger a reinstall of a server
     *
     * @param int $server_id
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function reinstall($server_id)
    {
        return $this->request('POST', '/api/application/servers/' . $server_id . '/reinstall');
    }

    /**
     * Delete a server
     *
     * @param bool $force
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function delete($server_id, $force = false)
    {
        $endpoint = '/api/application/servers/' . $server_id;

        if ($force) {
            $endpoint .= '/force';
        }

        return $this->request('DELETE', $endpoint);
    }
}