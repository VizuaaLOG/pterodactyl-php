<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Psr7\Response;
use VizuaaLOG\Pterodactyl\Pterodactyl;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\BadResponseException;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;

class Manager
{
    /**
     * @var \VizuaaLOG\Pterodactyl\Pterodactyl
     */
    protected $pterodactyl;

    /**
     * An instance of the http client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

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
     * @param null   $values
     * @param bool   $asResource
     *
     * @return array|\VizuaaLOG\Pterodactyl\Managers\ServerManager
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException
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
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return \VizuaaLOG\Pterodactyl\Managers\ServerManager|array
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
     * @param $object
     * @param $createResource
     *
     * @return array|\VizuaaLOG\Pterodactyl\Resources\Server
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

        $resourceClass = '\\VizuaaLOG\\Pterodactyl\\Resources\\' . ucwords($object['object']);

        $resource = new $resourceClass($object['attributes'], $this->pterodactyl);

        foreach($relationships as $key => $value) {
            $resource->$key = $this->transformObject($value);
        }

        return $resource;
    }

    public function throwException(BadResponseException $e)
    {
        $error = json_decode($e->getResponse()->getBody());
        throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
    }
}