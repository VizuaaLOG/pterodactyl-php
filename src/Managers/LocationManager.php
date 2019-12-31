<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use VizuaaLOG\Pterodactyl\Resources\Location;

class LocationManager extends Manager
{
    /**
     * @var string The resource this manager uses.
     */
    protected static $resource = Location::class;

    /**
     * Get all servers available.
     *
     * @return array<\VizuaaLOG\Pterodactyl\Resources\Location>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/locations');
    }

    /**
     * Get a single server object.
     *
     * @param int $location_id
     * @param array $includes
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\Location
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function get($location_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/locations/' . $location_id, null, true, $includes);
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
     * @return array|\VizuaaLOG\Pterodactyl\Resources\Location
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function create($values)
    {
        return $this->request('POST', '/api/application/locations', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int   $location_id
     * @param array $values
     *
     * @return array|Location
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
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
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function delete($location_id)
    {
        return $this->request('DELETE', '/api/application/locations/' . $location_id);
    }
}