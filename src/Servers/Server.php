<?php

namespace VizuaaLOG\Pterodactyl\Servers;

use VizuaaLOG\Pterodactyl\Resource;

class Server extends Resource {
    public function update($values)
    {
        if(!isset($values['user'])) {
            $values['user'] = $this->user;
        }

        $this->fill($this->pterodactyl->servers->update($this->id, $values));

        return $this;
    }

    public function updateBuild($values)
    {
        $this->fill($this->pterodactyl->servers->updateBuild($this->id, $values));
        $this->rebuild();

        return $this;
    }

    public function updateStartup($values)
    {
        $this->fill($this->pterodactyl->servers->updateStartup($this->id, $values));

        return $this;
    }

    public function suspend()
    {
        return $this->pterodactyl->servers->suspend($this->id);
    }

    public function unsuspend()
    {
        return $this->pterodactyl->servers->unsuspend($this->id);
    }

    public function reinstall()
    {
        return $this->pterodactyl->servers->reinstall($this->id);
    }

    public function rebuild()
    {
        return $this->pterodactyl->servers->rebuild($this->id);
    }

    /**
     * Delete a server
     * @param bool $force
     * @
     */
    public function delete($force = false)
    {
        return $this->pterodactyl->servers->delete($this->id, $force);
    }
}