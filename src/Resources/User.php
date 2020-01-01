<?php

namespace VizuaaLOG\Pterodactyl\Resources;

use GuzzleHttp\Exception\GuzzleException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;

class User extends Resource
{
    /**
     * Update an existing user's details
     *
     * @param array $values
     *
     * @return User
     *
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    public function update($values)
    {
        // Setup the base update payload based on what the panel requires, these will then be merged
        // with what has been provided.
        $base = [
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        ];

        $this->fill($this->pterodactyl->users->update($this->id, array_merge_recursive_distinct($base, $values)));

        return $this;
    }

    /**
     * Delete a user
     *
     * @return bool
     *
     * @throws GuzzleException
     */
    public function delete()
    {
        return $this->pterodactyl->users->delete($this->id);
    }
}