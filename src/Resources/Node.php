<?php

namespace VizuaaLOG\Pterodactyl\Resources;

class Node extends Resource
{
    /**
     * Update an existing node's details
     *
     * @param array $values
     *
     * @return \VizuaaLOG\Pterodactyl\Resources\Node
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function update($values)
    {
        $this->fill($this->pterodactyl->nodes->update($this->id, $values));

        return $this;
    }

    /**
     * Update an existing node's details
     *
     * @return array<\VizuaaLOG\Pterodactyl\Resources\Allocation>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function allocations()
    {
        return $this->pterodactyl->nodes->allocations($this->id);
    }

    /**
     * Update an existing node's details
     *
     * @param array $values
     *
     * @return \VizuaaLOG\Pterodactyl\Resources\Allocation
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function createAllocation($values)
    {
        return $this->pterodactyl->nodes->createAllocation($this->id, $values);
    }

    /**
     * Update an existing node's details
     *
     * @param int $allocation_id
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function deleteAllocation($allocation_id)
    {
        return $this->pterodactyl->nodes->deleteAllocation($this->id, $allocation_id);
    }

    /**
     * Delete a node
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function delete()
    {
        return $this->pterodactyl->nodes->delete($this->id);
    }
}