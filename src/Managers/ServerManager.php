<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Resources\Server;
use VizuaaLOG\Pterodactyl\Resources\ServerDatabase;
use VizuaaLOG\Pterodactyl\Resources\Stats;

class ServerManager extends Manager
{
    /** @var string */
    protected static $resource = Server::class;

    /**
     * Get all servers available.
     *
     * @return Server[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Server|false
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function get($server_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/' . $this->pterodactyl->api_type . '/servers/' . $server_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
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
     * @return Server|false
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function getByExternalId($external_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/servers/external/' . $external_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
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
     * @return Server
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Server
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Server
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function updateBuild($server_id, $values)
    {
        return $this->request('PATCH', '/api/application/servers/' . $server_id . '/build', $values, false);
    }

    /**
     * Update a server's startup configuration
     *
     * @param int $server_id
     * @param array $values
     *
     * @return Server
     *
     * @throws PterodactylRequestException
     * @throws GuzzleException
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
     * @return bool
     *
     * @throws GuzzleException
     */
    public function rebuild($server_id)
    {
        try {
            $this->request('POST', '/api/application/servers/' . $server_id . '/rebuild');

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Suspend a server
     *
     * @param int $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     *
     */
    public function suspend($server_id)
    {
        try {
            $this->request('POST', '/api/application/servers/' . $server_id . '/suspend');

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Unsuspend a server
     *
     * @param int $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     *
     */
    public function unsuspend($server_id)
    {
        try {
            $this->request('POST', '/api/application/servers/' . $server_id . '/unsuspend');

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Trigger a reinstall of a server
     *
     * @param int $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     *
     */
    public function reinstall($server_id)
    {
        try {
            $this->request('POST', '/api/application/servers/' . $server_id . '/reinstall');

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Start a server
     *
     * @param string $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function start($server_id)
    {
        try {
            $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'start']);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Stop a server
     *
     * @param string $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function stop($server_id)
    {
        try {
            $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'stop']);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Restart a server
     *
     * @param string $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function restart($server_id)
    {
        try {
            $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'restart']);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Kill a server
     *
     * @param string $server_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function kill($server_id)
    {
        try {
            $this->request('POST', '/api/client/servers/' . $server_id . '/power', ['signal' => 'kill']);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Get a server's utilization
     *
     * @param string $server_id
     *
     * @return Stats
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function sendCommand($server_id, $command)
    {
        try {
            $this->request('POST', '/api/client/servers/' . $server_id . '/command', [ 'command' => $command ]);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Get a server's databases
     *
     * @param int $server_id
     *
     * @return ServerDatabase[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     *
     * @return ServerDatabase
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function database($server_id, $database_id)
    {
        try {
            return $this->request('GET', '/api/application/servers/' . $server_id . '/databases/' . $database_id, null, true);
        } catch(PterodactylRequestException $exception) {
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
     * @return ServerDatabase
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return bool
     *
     * @throws GuzzleException
     */
    public function resetDatabasePassword($server_id, $database_id)
    {
        try {
            $this->request('POST', '/api/application/servers/' . $server_id . '/databases/' . $database_id . '/reset-password');

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Delete a database
     *
     * @param int $server_id
     * @param int $database_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function deleteDatabase($server_id, $database_id)
    {
        try {
            $this->request('DELETE', '/api/application/servers/' . $server_id . '/databases/' . $database_id);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Delete a server
     *
     * @param int $server_id
     * @param bool $force
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function delete($server_id, $force = false)
    {
        try {
            $endpoint = '/api/application/servers/' . $server_id;

            if ($force) {
                $endpoint .= '/force';
            }

            $this->request('DELETE', $endpoint);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }
}