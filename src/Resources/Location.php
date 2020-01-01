<?php

namespace VizuaaLOG\Pterodactyl\Resources;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Managers\ServerManager;

class Location extends Resource
{
    /**
     * Update an existing user's details
     *
     * @param array $values
     *
     * @return Location
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function update($values)
    {
        // Setup the base update payload based on what the panel requires, these will then be merged
        // with what has been provided.
        $base = [
            'short' => $this->short,
            'long' => $this->long
        ];

        $this->fill($this->pterodactyl->locations->update($this->id, array_merge_recursive_distinct($base, $values)));

        return $this;
    }

    /**
     * Delete a user
     *
     * @return bool
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function delete()
    {
        return $this->pterodactyl->locations->delete($this->id);
    }
}