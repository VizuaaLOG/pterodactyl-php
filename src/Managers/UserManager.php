<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use VizuaaLOG\Pterodactyl\Resources\User;

class UserManager extends Manager
{
    /**
     * @var string The resource this manager uses.
     */
    protected static $resource = User::class;

    /**
     * Get all servers available.
     *
     * @return array<\VizuaaLOG\Pterodactyl\Resources\User>
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/users');
    }

    /**
     * Get a single server object.
     *
     * @param int $user_id
     * @param array $includes
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\User
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function get($user_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/users/' . $user_id, null, true, $includes);
        } catch(\VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Get a single server object using the external id.
     *
     * @param mixed $external_id
     * @param array $includes
     *
     * @return bool|array|\VizuaaLOG\Pterodactyl\Resources\User
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function getByExternalId($external_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/users/external/' . $external_id, null, true, $includes);
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
     * @return array|\VizuaaLOG\Pterodactyl\Resources\User
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function create($values)
    {
        return $this->request('POST', '/api/application/users', $values);
    }

    /**
     * Update a server's configuration
     *
     * @param int   $user_id
     * @param array $values
     *
     * @return array|User
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function update($user_id, $values)
    {
        return $this->request('PATCH', '/api/application/users/' . $user_id, $values);
    }

    /**
     * Delete a server
     *
     * @param int $user_id
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
     */
    public function delete($user_id)
    {
        return $this->request('DELETE', '/api/application/users/' . $user_id);
    }
}