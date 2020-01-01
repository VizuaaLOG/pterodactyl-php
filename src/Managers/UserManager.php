<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Resources\User;

class UserManager extends Manager
{
    /** @var string */
    protected static $resource = User::class;

    /**
     * Get all users available.
     *
     * @return User[]
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function all()
    {
        return $this->request('GET', '/api/application/users');
    }

    /**
     * Get a single user object.
     *
     * @param int $user_id
     * @param array $includes
     *
     * @return User|false
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function get($user_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/users/' . $user_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
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
     * @return User|false
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function getByExternalId($external_id, $includes = [])
    {
        try {
            return $this->request('GET', '/api/application/users/external/' . $external_id, null, true, $includes);
        } catch(PterodactylRequestException $exception) {
            if(strstr($exception->getMessage(), 'NotFoundHttpException') !== false) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * Create a new user
     *
     * @param array $values
     *
     * @return User
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function create($values)
    {
        return $this->request('POST', '/api/application/users', $values);
    }

    /**
     * Update a user's configuration
     *
     * @param int $user_id
     * @param array $values
     *
     * @return User
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function update($user_id, $values)
    {
        return $this->request('PATCH', '/api/application/users/' . $user_id, $values);
    }

    /**
     * Delete a user
     *
     * @param int $user_id
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function delete($user_id)
    {
        try {
            $this->request('DELETE', '/api/application/users/' . $user_id);

            return true;
        } catch(PterodactylRequestException $exception) {
            return false;
        }
    }
}