<?php

namespace VizuaaLOG\Pterodactyl\Servers;

class Server {
    /**
     * @var int
     */
    public $id;

    /**
     * @var int|null
     */
    public $external_id;

    /**
     * @var string
     */
    public $uuid;

    /**
     * @var string
     */
    public $identifier;
    
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $suspended;

    /**
     * @var array
     */
    public $limits;

    /**
     * @var array
     */
    public $feature_limits;

    /**
     * @var int
     */
    public $user;

    /**
     * @var int
     */
    public $node;

    /**
     * @var int
     */
    public $allocation;

    /**
     * @var int
     */
    public $nest;

    /**
     * @var int
     */
    public $egg;

    /**
     * @var int
     */
    public $pack;

    /**
     * @var array
     */
    public $container;
    
    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var string
     */
    public $created_at;

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

    public function delete($force = false)
    {

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