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
        // Setup the base update payload based on what the panel requires, these will then be merged
        // with what has been provided.
        $base = [
            'name' => $this->name,
            'location_id' => $this->locationId,
            'public' => $this->public,
            'fqdn' => $this->fqdn,
            'scheme' => $this->scheme,
            'behind_proxy' => $this->behindProxy,
            'memory' => $this->memory,
            'memory_overallocate' => $this->memoryOverallocate,
            'disk' => $this->disk,
            'disk_overallocate' => $this->diskOverallocate,
            'daemon_base' => $this->daemonBase,
            'daemon_sftp' => $this->daemonSftp,
            'daemon_listen' => $this->daemonListen,
            'maintenance_mode' => $this->maintenanceMode,
            'upload_size' => $this->uploadSize,
        ];

        $this->fill($this->pterodactyl->nodes->update($this->id, array_merge_recursive_distinct($base, $values)));

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