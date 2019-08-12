<?php

namespace VizuaaLOG\Pterodactyl\Servers;

use VizuaaLOG\Pterodactyl\Resource;

class Server extends Resource {
    public function update($values)
    {
        
    }

    public function updateBuild($values)
    {

    }

    public function updateStartup($values)
    {

    }

    public function suspend()
    {

    }

    public function unsuspend()
    {

    }

    public function reinstall()
    {

    }

    public function rebuild()
    {

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

    public function databases()
    {

    }

    public function database($id)
    {

    }

    public function createDatabase()
    {

    }

    public function resetDatabasePassword()
    {

    }

    public function deleteDatabase()
    {
        
    }
}