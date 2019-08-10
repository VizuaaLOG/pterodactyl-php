<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use VizuaaLOG\Pterodactyl\Pterodactyl;
use VizuaaLOG\Pterodactyl\Servers\Server;

class ServerManagerTest extends TestCase {
    public function test_all_servers_are_returned_as_server_objects()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/json_responses/all-servers.json'))
        ]);

        $api = new Pterodactyl(
            'test_key',
            'https://test-server.dev',
            new Client(['handler' => HandlerStack::create($mock)])
        );

        $servers = $api->servers->all();

        $this->assertCount(2, $servers);
        $this->assertInstanceOf(Server::class, $servers[0]);
        $this->assertInstanceOf(Server::class, $servers[1]);
    }

    public function test_a_single_server_can_be_returned_as_a_server_object()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/json_responses/single-server.json'))
        ]);

        $api = new Pterodactyl(
            'test_key',
            'https://test-server.dev',
            new Client(['handler' => HandlerStack::create($mock)])
        );

        $server = $api->servers->get(2);

        $this->assertInstanceOf(Server::class, $server);
    }

    public function test_false_is_returned_if_no_server_was_found()
    {
        $mock = new MockHandler([
            new Response(404, ['Content-Type' => 'text/html; charset=UTF-8'])
        ]);

        $api = new Pterodactyl(
            'test_key',
            'https://test-server.dev',
            new Client(['handler' => HandlerStack::create($mock)])
        );

        $server = $api->servers->get(2);

        $this->assertFalse($server);
    }

    public function test_a_server_can_be_returned_by_external_id()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/json_responses/single-server.json'))
        ]);

        $api = new Pterodactyl(
            'test_key',
            'https://test-server.dev',
            new Client(['handler' => HandlerStack::create($mock)])
        );

        $server = $api->servers->getByExternalId(99);

        $this->assertInstanceOf(Server::class, $server);
    }
}