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
        if($this->pterodactyl->api_type === 'client') {
            return $this->request('GET', '/api/client');
        }

        return $this->request('GET', '/api/' . $this->pterodactyl->api_type . '/servers');
    }

    /**
     * Get a single server object.
     *
     * @param int|string $server_id
     * @param array $includes
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function get($server_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/' . $this->pterodactyl->api_type . '/servers/' . $server_id, null, true, $includes);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Get a single server object using the external id.
     *
     * @param mixed $external_id
     * @param array $includes
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function getByExternalId($external_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/servers/external/' . $external_id, null, true, $includes);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
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
     * Start a server
     *
     * @param string $server_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function start($server_id)
    {
        return $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'start']);
    }

    /**
     * Stop a server
     *
     * @param string $server_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function stop($server_id)
    {
        return $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'stop']);
    }

    /**
     * Restart a server
     *
     * @param string $server_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function restart($server_id)
    {
        return $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'restart']);
    }

    /**
     * Kill a server
     *
     * @param string $server_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function kill($server_id)
    {
        return $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'kill']);
    }

    /**
     * Get a server's utilization
     *
     * @param string $server_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function utilization($server_id)
    {
        return $this->request('GET', '/api/client/servers/' . $server_id . '/utilization');
    }

    /**
     * Send a command to a server.
     *
     * @param string $server_id
     * @param string $command
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function sendCommand($server_id, $command)
    {
        return $this->request('POST', '/api/client/servers/' . $server_id . '/command', [ 'command' => $command ]);
    }

    /**
     * Get a server's databases
     *
     * @param int $server_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function databases($server_id)
    {
        return $this->request('GET', '/api/application/servers/' . $server_id . '/databases', null, true);
    }

    /**
     * Get a server's database
     *
     * @param int $server_id
     * @param int $database_id
     * @return array|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function database($server_id, $database_id)
    {
        try {
            return $this->request('GET', '/api/application/servers/' . $server_id . '/databases/' . $database_id, null, true);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Create a new database for the server
     *
     * @param int $server_id
     * @param array $values
     *
     * @return Database|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function createDatabase($server_id, $values)
    {
        return $this->request('POST', '/api/application/servers/' . $server_id . '/databases', $values, true);
    }

    /**
     * Reset a database's password
     *
     * @param int $server_id
     * @param int $database_id
     *
     * @return Database|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function resetDatabasePassword($server_id, $database_id)
    {
        return $this->request('POST', '/api/application/servers/' . $server_id . '/databases/' . $database_id . '/reset-password');
    }

    /**
     * Delete a database
     *
     * @param int $server_id
     * @param int $database_id
     *
     * @return null|ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function deleteDatabase($server_id, $database_id)
    {
        return $this->request('DELETE', '/api/application/servers/' . $server_id . '/databases/' . $database_id);
    }

    /**
     * Delete a server
     *
     * @param int $server_id
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