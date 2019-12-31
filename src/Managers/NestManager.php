<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use VizuaaLOG\Pterodactyl\Resources\Nest;

class NestManager extends Manager
{
    /**
     * @var string The resource this manager uses.
     */
    protected static $resource = Nest::class;

    /**
     * Get all nests available.
     *
     * @return array<\VizuaaLOG\Pterodactyl\Resources\Nest>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
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
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\Nest
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function get($nest_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/nests/' . $nest_id, null, true, $includes);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
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
     * @return array<\VizuaaLOG\Pterodactyl\Resources\Egg>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
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
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\Egg
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function egg($nest_id, $egg_id)
    {
        try {
            return $this->request('GET', '/api/application/nests/' . $nest_id . '/eggs/' . $egg_id, null, true, ['variables']);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }
}