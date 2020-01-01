<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Resources\Location;

class LocationManager extends Manager
{
    /** @var string */
    protected static $resource = Location::class;

    /**
     * Get all servers available.
     *
     * @return Location[]
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/locations');
    }

    /**
     * Get a single server object.
     *
     * @param int $location_id
     * @param string[] $includes
     *
     * @return Location|false
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function get($location_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/locations/' . $location_id, null, true, $includes);
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
     * @return Location
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function create($values)
    {
        return $this->request('POST', '/api/application/locations', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int $location_id
     * @param array $values
     *
     * @return Location
     * @throws GuzzleException
     *
     * @throws PterodactylRequestException
     */
    public function update($location_id, $values)
    {
        return $this->request('PATCH', '/api/application/locations/' . $location_id, $values);
    }

    /**
     * Delete a server
     *
     * @param int $location_id
     *
     * @return bool
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function delete($location_id)
    {
        try {
            $this->request('DELETE', '/api/application/locations/' . $location_id);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }
}