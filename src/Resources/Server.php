<?php

namespace VizuaaLOG\Pterodactyl\Resources;

class Server extends Resource
{
    /**
     * Update an existing server's configuration
     *
     * @param array $values
     *
     * @return \VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function update($values)
    {
        if (!isset($values['user'])) {
            $values['user'] = $this->user;
        }

        $this->fill($this->pterodactyl->servers->update($this->id, $values));

        return $this;
    }

    /**
     * Update an existing server's build configuration
     *
     * @param array $values
     *
     * @return \VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function updateBuild($values)
    {
        // The API requires all of these values. Set them to the current values
        // if they are not supplied.
        $values = array_merge([
            "allocation" => $this->allocation,
            "memory" => $this->limits['memory'],
            "swap" => $this->limits['swap'],
            "io" => $this->limits['io'],
            "cpu" => $this->limits['cpu'],
            "disk" => $this->limits['disk'],
            "feature_limits" => [
                "databases" => $this->featureLimits['databases'],
                "allocations" => $this->featureLimits['allocations']
            ]
        ], $values);

        $this->fill($this->pterodactyl->servers->updateBuild($this->id, $values));
        $this->rebuild();

        return $this;
    }

    /**
     * Trigger a rebuild of the server
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
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
     * @return \VizuaaLOG\Pterodactyl\Resources\Server
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function updateStartup($values)
    {
        $this->fill($this->pterodactyl->servers->updateStartup($this->id, $values));

        return $this;
    }

    /**
     * Suspend a server
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function suspend()
    {
        return $this->pterodactyl->servers->suspend($this->id);
    }

    /**
     * Unsuspend a server
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function unsuspend()
    {
        return $this->pterodactyl->servers->unsuspend($this->id);
    }

    /**
     * Trigger a reinstall of the server
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function reinstall()
    {
        return $this->pterodactyl->servers->reinstall($this->id);
    }

    /**
     * Start this server
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function start()
    {
        $this->pterodactyl->servers->start($this->identifier);
    }

    /**
     * Stop this server
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function stop()
    {
        $this->pterodactyl->servers->stop($this->identifier);
    }

    /**
     * Stop this server
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function restart()
    {
        $this->pterodactyl->servers->restart($this->identifier);
    }

    /**
     * Stop this server
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function kill()
    {
        $this->pterodactyl->servers->kill($this->identifier);
    }

    /**
     * Get this server's utilization
     *
     * @return Stats|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function utilization()
    {
        return $this->pterodactyl->servers->utilization($this->identifier);
    }

    /**
     * Send a command to the server.
     *
     * @param string $command
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function sendCommand($command)
    {
        $this->pterodactyl->servers->sendCommand($this->identifier, $command);
    }

    /**
     * Get this server's databases.
     *
     * @return Database|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function databases()
    {
        return $this->pterodactyl->servers->databases($this->id);
    }

    /**
     * Get this server's databases.
     *
     * @param int $database_id
     *
     * @return Database|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function database($database_id)
    {
        return $this->pterodactyl->servers->database($this->id, $database_id);
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
    public function delete($force = false)
    {
        return $this->pterodactyl->servers->delete($this->id, $force);
    }
}