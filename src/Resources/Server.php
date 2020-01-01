<?php

namespace VizuaaLOG\Pterodactyl\Resources;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Managers\ServerManager;

class Server extends Resource
{
    /**
     * Update an existing server's configuration
     *
     * @param array $values
     *
     * @return Server
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function update($values)
    {
        // Setup the base update payload based on what the panel requires, these will then be merged
        // with what has been provided.
        $base = [
            'name' => $this->name,
            'user' => $this->user,
        ];

        $this->fill($this->pterodactyl->servers->update($this->id, array_merge_recursive_distinct($base, $values)));

        return $this;
    }

    /**
     * Update an existing server's build configuration
     *
     * @param array $values
     *
     * @return Server
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function updateBuild($values)
    {
        // Setup the base update payload based on what the panel requires, these will then be merged
        // with what has been provided.
        $base = [
            "allocation" => $this->allocation,
            "limits" => $this->limits,
            "feature_limits" => $this->featureLimits,
        ];

        $this->fill($this->pterodactyl->servers->updateBuild($this->id, array_merge_recursive_distinct($base, $values)));
        $this->rebuild();

        return $this;
    }

    /**
     * Trigger a rebuild of the server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function rebuild()
    {
        return $this->pterodactyl->servers->rebuild($this->id);
    }

    /**
     * Update an existing server's startup configuration
     *
     * @param array $values
     *
     * @return Server
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function updateStartup($values)
    {
        // Setup the base update payload based on what the panel requires, these will then be merged
        // with what has been provided.
        $base = [
            "startup" => $this->container['startup_command'],
            "egg" => $this->egg,
            "image" => $this->container['image'],
            "environment" => $this->container['environment']
        ];

        $this->fill($this->pterodactyl->servers->updateStartup($this->id, array_merge_recursive_distinct($base, $values)));

        return $this;
    }

    /**
     * Suspend a server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function suspend()
    {
        return $this->pterodactyl->servers->suspend($this->id);
    }

    /**
     * Unsuspend a server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function unsuspend()
    {
        return $this->pterodactyl->servers->unsuspend($this->id);
    }

    /**
     * Trigger a reinstall of the server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function reinstall()
    {
        return $this->pterodactyl->servers->reinstall($this->id);
    }

    /**
     * Start this server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function start()
    {
        return $this->pterodactyl->servers->start($this->identifier);
    }

    /**
     * Stop this server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function stop()
    {
        return $this->pterodactyl->servers->stop($this->identifier);
    }

    /**
     * Stop this server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function restart()
    {
        return $this->pterodactyl->servers->restart($this->identifier);
    }

    /**
     * Stop this server
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function kill()
    {
        return $this->pterodactyl->servers->kill($this->identifier);
    }

    /**
     * Get this server's utilization
     *
     * @return Stats
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function utilization()
    {
        return $this->pterodactyl->servers->utilization($this->identifier);
    }

    /**
     * Send a command to the server.
     *
     * @param string $command
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function sendCommand($command)
    {
        return $this->pterodactyl->servers->sendCommand($this->identifier, $command);
    }

    /**
     * Get this server's databases.
     *
     * @return ServerDatabase[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function databases()
    {
        return $this->pterodactyl->servers->databases($this->id);
    }

    /**
     * Get this server's databases.
     *
     * @param int $database_id
     * @param array $includes
     *
     * @return ServerDatabase
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function database($database_id, $includes = [])
    {
        return $this->pterodactyl->servers->database($this->id, $database_id, $includes);
    }

    /**
     * Create a database for this server
     *
     * @param array $values
     *
     * @return ServerDatabase
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function createDatabase($values)
    {
        return $this->pterodactyl->servers->createDatabase($this->id, $values);
    }

    /**
     * Reset a database password
     *
     * @param int $database_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function resetDatabasePassword($database_id)
    {
        return $this->pterodactyl->servers->resetDatabasePassword($this->id, $database_id);
    }

    /**
     * Delete a database
     *
     * @param int $database_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function deleteDatabase($database_id)
    {
        return $this->pterodactyl->servers->deleteDatabase($this->id, $database_id);
    }

    /**
     * Delete a server
     *
     * @param bool $force
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function delete($force = false)
    {
        return $this->pterodactyl->servers->delete($this->id, $force);
    }
}