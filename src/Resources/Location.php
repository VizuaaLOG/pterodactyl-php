<?php

namespace VizuaaLOG\Pterodactyl\Resources;

class Location extends Resource
{
    /**
     * Update an existing user's details
     *
     * @param array $values
     *
     * @return \VizuaaLOG\Pterodactyl\Resources\Location
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function update($values)
    {
        $this->fill($this->pterodactyl->locations->update($this->id, $values));

        return $this;
    }

    /**
     * Delete a user
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function delete()
    {
        return $this->pterodactyl->locations->delete($this->id);
    }
}