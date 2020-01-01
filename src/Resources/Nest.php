<?php

namespace VizuaaLOG\Pterodactyl\Resources;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;

class Nest extends Resource
{
    /**
     * Get this nests eggs.
     *
     * @return Egg[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function eggs()
    {
        return $this->pterodactyl->nests->eggs($this->id);
    }

    /**
     * Get an egg from this nest.
     *
     * @param int $egg_id
     * @param array $includes
     *
     * @return Egg
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function egg($egg_id, $includes = ['variables'])
    {
        return $this->pterodactyl->nests->egg($this->id, $egg_id, $includes);
    }
}