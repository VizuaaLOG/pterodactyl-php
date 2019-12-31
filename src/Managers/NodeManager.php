<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use VizuaaLOG\Pterodactyl\Resources\Allocation;
use VizuaaLOG\Pterodactyl\Resources\Node;

class NodeManager extends Manager
{
    /**
     * @var string The resource this manager uses.
     */
    protected static $resource = Node::class;

    /**
     * Get all servers available.
     *
     * @return array<\VizuaaLOG\Pterodactyl\Resources\Node>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/nodes');
    }

    /**
     * Get a single server object.
     *
     * @param int $node_id
     * @param array $includes
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\Node
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function get($node_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/nodes/' . $node_id, null, true, $includes);
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
     * @return array|\VizuaaLOG\Pterodactyl\Resources\Node
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function create($values)
    {
        return $this->request('POST', '/api/application/nodes', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int   $node_id
     * @param array $values
     *
     * @return array|Node
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function update($node_id, $values)
    {
        try {
            return $this->request('PATCH', '/api/application/nodes/' . $node_id, $values);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
            // If the node is unable to communicate with daemon a different exception is thrown. This is not related
            // to the update failing.
            if(strstr($exception->getMessage(), 'ConfigurationNotPersistedException') === false) {
                throw $exception;
            }
        }
    }

    /**
     * Update a server's configuration
     *
     * @param int $node_id
     *
     * @return array<Allocation>
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function allocations($node_id)
    {
        return $this->request('GET', '/api/application/nodes/' . $node_id . '/allocations');
    }

    /**
     * Update a server's configuration
     *
     * @param int $node_id
     * @param array $values
     *
     * @return array<Allocation>
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function createAllocation($node_id, $values)
    {
        return $this->request('POST', '/api/application/nodes/' . $node_id . '/allocations', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int $node_id
     * @param array $values
     *
     * @return array<Allocation>
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function deleteAllocation($node_id, $allocation_id)
    {
        return $this->request('DELETE', '/api/application/nodes/' . $node_id . '/allocations/' . $allocation_id);
    }

    /**
     * Delete a server
     *
     * @param int $node_id
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function delete($node_id)
    {
        return $this->request('DELETE', '/api/application/nodes/' . $node_id);
    }
}