<?php

namespace VizuaaLOG\Pterodactyl\Resources;

class Server extends Resource {
    /**
     * Update an existing server's configuration
     * @param array $values
     * @return \VizuaaLOG\Pterodactyl\Resources\Server
     */
    public function update($values)
    {
        if(!isset($values['user'])) {
            $values['user'] = $this->user;
        }

        $this->fill($this->pterodactyl->servers->update($this->id, $values));

        return $this;
    }

    /**
     * Update an existing server's build configuration
     * @param array $values
     * @return \VizuaaLOG\Pterodactyl\Resources\Server
     */
    public function updateBuild($values)
    {
        $this->fill($this->pterodactyl->servers->updateBuild($this->id, $values));
        $this->rebuild();

        return $this;
    }

    /**
     * Update an existing server's startup configuration
     * @param array $values
     * @return \VizuaaLOG\Pterodactyl\Resources\Server
     */
    public function updateStartup($values)
    {
        $this->fill($this->pterodactyl->servers->updateStartup($this->id, $values));

        return $this;
    }

    /**
     * Suspend a server
     * @return bool
     */
    public function suspend()
    {
        return $this->pterodactyl->servers->suspend($this->id);
    }

    /**
     * Unsuspend a server
     * @return bool
     */
    public function unsuspend()
    {
        return $this->pterodactyl->servers->unsuspend($this->id);
    }

    /**
     * Trigger a reinstall of the server
     * @return bool
     */
    public function reinstall()
    {
        return $this->pterodactyl->servers->reinstall($this->id);
    }

    /**
     * Trigger a rebuild of the server
     * @return bool
     */
    public function rebuild()
    {
        return $this->pterodactyl->servers->rebuild($this->id);
    }

    /**
     * Delete a server
     * @param bool $force
     * @return bool
     */
    public function delete($force = false)
    {
        return $this->pterodactyl->servers->delete($this->id, $force);
    }
}