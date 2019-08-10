<?php

use PHPUnit\Framework\TestCase;
use VizuaaLOG\Pterodactyl\Exceptions\InvalidApiKeyException;
use VizuaaLOG\Pterodactyl\Pterodactyl;
use VizuaaLOG\Pterodactyl\Exceptions\InvalidBaseUriException;

class PterodactylTest extends TestCase {
    public function test_an_exception_is_thrown_if_no_api_key_provided()
    {
        $this->expectException(InvalidApiKeyException::class);

        new Pterodactyl(null, 'demo.com');
    }

    public function test_an_exception_is_thrown_if_no_base_uri_is_provided()
    {
        $this->expectException(InvalidBaseUriException::class);

        new Pterodactyl('api_key', null);
    }
}