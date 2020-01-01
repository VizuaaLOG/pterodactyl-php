<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Resources\Egg;
use VizuaaLOG\Pterodactyl\Resources\Nest;

class NestManager extends Manager
{
    /** @var string */
    protected static $resource = Nest::class;

    /**
     * Get all nests available.
     *
     * @return Nest[]
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/nests');
    }

    /**
     * Get a single server object.
     *
     * @param int $nest_id
     * @param array $includes
     *
     * @return Nest|false
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function get($nest_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/nests/' . $nest_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Get all eggs for nest.
     *
     * @param int $nest_id
     *
     * @return Egg[]
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function eggs($nest_id)
    {
        return $this->request('GET', '/api/application/nests/' . $nest_id . '/eggs');
    }

    /**
     * Get a single server object.
     *
     * @param int $nest_id
     * @param int $egg_id
     * @param array $includes
     *
     * @return Egg|false
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function egg($nest_id, $egg_id, $includes = ['variables'])
    {
        try {
            return $this->request('GET', '/api/application/nests/' . $nest_id . '/eggs/' . $egg_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }
}