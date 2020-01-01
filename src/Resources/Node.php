<?php

namespace VizuaaLOG\Pterodactyl\Resources;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Managers\ServerManager;

class Node extends Resource
{
    /**
     * Update an existing node's details
     *
     * @param array $values
     *
     * @return Node
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Allocation[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Allocation
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return bool
     *
     * @throws GuzzleException
     */
    public function deleteAllocation($allocation_id)
    {
        return $this->pterodactyl->nodes->deleteAllocation($this->id, $allocation_id);
    }

    /**
     * Delete a node
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function delete()
    {
        return $this->pterodactyl->nodes->delete($this->id);
    }
}