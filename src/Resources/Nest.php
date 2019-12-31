<?php

namespace VizuaaLOG\Pterodactyl\Resources;

class Nest extends Resource
{
    /**
     * Get this nests eggs.
     *
     * @return array<Egg>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function eggs()
    {
        return $this->pterodactyl->nests->eggs($this->id);
    }

    /**
     * Get an egg from this nest.
     *
     * @param int $egg_id
     *
     * @return Egg
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function egg($egg_id)
    {
        return $this->pterodactyl->nests->egg($this->id, $egg_id);
    }
}