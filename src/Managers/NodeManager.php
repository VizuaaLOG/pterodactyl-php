<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Resources\Allocation;
use VizuaaLOG\Pterodactyl\Resources\Node;

class NodeManager extends Manager
{
    /** @var string */
    protected static $resource = Node::class;

    /**
     * Get all servers available.
     *
     * @return Node[]
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Node|false
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function get($node_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/nodes/' . $node_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
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
     * @return Node
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Node|false
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function update($node_id, $values)
    {
        try {
            return $this->request('PATCH', '/api/application/nodes/' . $node_id, $values);
        } catch(PterodactylRequestException $exception) {
            // If the node is unable to communicate with daemon a different exception is thrown. This is not related
            // to the update failing.
            if(strstr($exception->getMessage(), 'ConfigurationNotPersistedException') === false) {
                throw $exception;
            }

            return false;
        }
    }

    /**
     * Update a server's configuration
     *
     * @param int $node_id
     *
     * @return Allocation[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
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
     * @return Allocation
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function createAllocation($node_id, $values)
    {
        return $this->request('POST', '/api/application/nodes/' . $node_id . '/allocations', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int $node_id
     * @param int $allocation_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function deleteAllocation($node_id, $allocation_id)
    {
        try {
            $this->request('DELETE', '/api/application/nodes/' . $node_id . '/allocations/' . $allocation_id);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }

    /**
     * Delete a server
     *
     * @param int $node_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function delete($node_id)
    {
        try {
            $this->request('DELETE', '/api/application/nodes/' . $node_id);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }
}