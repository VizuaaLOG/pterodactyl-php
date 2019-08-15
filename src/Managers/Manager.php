<?php

namespace VizuaaLOG\Pterodactyl\Managers;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use VizuaaLOG\Pterodactyl\Resources\Server;
use VizuaaLOG\Pterodactyl\Exceptions\PterodactylRequestException;

class Manager {
    /**
     * An instance of the http client.
     * @var \VizuaaLOG\Pterodactyl\Pterodactyl
     */
    protected $http;

    public function __construct($pterodactyl)
    {
        $this->pterodactyl = $pterodactyl;
        $this->http = $this->pterodactyl->http;
    }

    protected function getRequest($uri, $asResource = true)
    {
        try {
            return $this->transformResponse($this->http->request('GET', $uri), $asResource);
        } catch(ClientException $e) {
            $this->throwException($e);
        } catch(ServerException $e) {
            $this->throwException($e);
        }
    }

    protected function postRequest($uri, $values)
    {
        try {
            return $this->transformResponse(
                $this->http->request('POST', $uri, [
                    'form_params' => $values
                ])
            );
        } catch(ClientException $e) {
            $this->throwException($e);
        } catch(ServerException $e) {
            $this->throwException($e);
        }
    }

    public function throwException($e)
    {
        $error = json_decode($e->getResponse()->getBody());
        throw new PterodactylRequestException($error->errors[0]->code . ': ' . $error->errors[0]->detail);
    }

    /**
     * Convert a guzzle response into the correct resources
     * @param array $response
     * @param bool $createResource
     * @return \VizuaaLOG\Pterodactyl\Servers\Server|array
     */
    protected function transformResponse($response, $createResource = true)
    {
        $json = json_decode($response->getBody()->getContents());
        $output = [];
    
        if($json->object == 'list') {
            foreach($json->data as $jsonObject) {
                $output[] = $this->transformObject($jsonObject, $createResource);
            }

            return $output;
        }

        return $this->transformObject($json, $createResource);
    }

    protected function transformObject($object, $createResource)
    {
        if($createResource) {
            return new static::$resource($object->attributes, $this->pterodactyl);
        }
        
        return $object->attributes;
    }
}