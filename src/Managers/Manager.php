<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use VizuaaLOG\Pterodactyl\Pterodactyl;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;
use VizuaaLOG\Pterodactyl\Resources\Server;

class Manager
{
    /** @var Pterodactyl */
    protected $pterodactyl;

    /** @var Client */
    protected $http;

    /** @param Pterodactyl $pterodactyl */
    public function __construct($pterodactyl)
    {
        $this->pterodactyl = $pterodactyl;
        $this->http = $this->pterodactyl->http;
    }

    /**
     * Send a request to the Pterodactyl server.
     *
     * @param string $method
     * @param string $uri
     * @param null|array $values
     * @param bool $asResource
     * @param array $includes
     *
     * @return mixed
     * @throws GuzzleException
     * @throws PterodactylRequestException
     */
    protected function request($method, $uri, $values = null, $asResource = true, $includes = [])
    {
        try {
            if ($method === 'GET') {
                return $this->transformResponse($this->http->request($method, $uri . '?include=' . implode(',', $includes)));
            }

            return $this->transformResponse($this->http->request($method, $uri, [
                'form_params' => $values,
            ]));
        } catch (ClientException $e) {
            $this->throwException($e);
        } catch (ServerException $e) {
            $this->throwException($e);
        }
    }

    /**
     * Convert a guzzle response into the correct resources
     *
     * @param Response $response
     *
     * @return mixed
     */
    protected function transformResponse(Response $response)
    {
        $json = json_decode($response->getBody()->getContents(), true);

        if(empty($json)) {
            return [];
        }

        return $this->transformObject($json);
    }

    /**
     * Transform an API response.
     *
     * @param array $object
     * @return mixed
     */
    protected function transformObject($object)
    {
        $output = [];

        if($object['object'] === 'list') {
            foreach($object['data'] as $record) {
                $output[] = $this->transformObject($record);
            }

            return $output;
        }

        $relationships = [];

        // Process the relationships
        if(isset($object['attributes']['relationships'])) {
            $relationships = $object['attributes']['relationships'];

            unset($object['attributes']['relationships']);
        }

        $resourceClass = '\\VizuaaLOG\\Pterodactyl\\Resources\\' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $object['object'])));

        $resource = new $resourceClass($object['attributes'], $this->pterodactyl);

        foreach($relationships as $key => $value) {
            $resource->$key = $this->transformObject($value);
        }

        return $resource;
    }

    /**
     * Process and throw the exception.
     *
     * @param BadResponseException $e
     * @throws PterodactylRequestException
     */
    public function throwException(BadResponseException $e)
    {
        $error = json_decode($e->getResponse()->getBody());
        throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
    }
}