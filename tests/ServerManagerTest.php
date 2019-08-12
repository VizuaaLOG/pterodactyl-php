<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use VizuaaLOG\Pterodactyl\Pterodactyl;
use VizuaaLOG\Pterodactyl\Servers\Server;

class ServerManagerTest extends TestCase {
    public function createClient($responses = [])
    {
        return new Pterodactyl(
            'eFN6Qw7GEYdOg770AsKUouNTAtdojFb4hShk4N4kKfpM2paT',
            'https://mcadmin.hyperionserver.com'
            // new Client(['handler' => HandlerStack::create(new MockHandler($responses))])
        );
    }

    public function createServer($api, $name)
    {
        return $api->servers->create([
            "name" => "$name",
            "user" => 1,
            "nest" => 1,
            "egg" => 15,
            "docker_image" => "quay.io/parkervcp/pterodactyl-images:debian_openjdk-8-jre",
            "startup" => "java -Xms128M -Xmx 512M -jar server.jar",
            "limits" => [
                "memory" => 512,
                "swap" => 0,
                "disk" => 1024,
                "io" => 500,
                "cpu" => 100
            ],
            "feature_limits" => [
                "databases" => 1,
                "allocations" => 0
            ],
            "environment" => [
                "DL_VERSION" => "1.12.2",
                "SERVER_JARFILE" => "server.jar"
            ],
            "deploy" => [
                "locations" => [1],
                "dedicated_ip" => false,
                "port_range" => ["25570"]
            ],
            "start_on_completion" => false
        ]);
    }

    public function test_all_servers_are_returned_as_server_objects()
    {
        $api = $this->createClient([new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/json_responses/all-servers.json'))]);
        $servers = $api->servers->all();
        
        $this->assertInstanceOf(Server::class, $servers[0]);
        $this->assertInstanceOf(Server::class, $servers[1]);
    }

    public function test_a_single_server_can_be_returned_as_a_server_object()
    {
        $api = $this->createClient([new Response(200, ['Content-Type' => 'application/json'], file_get_contents(__DIR__ . '/json_responses/single-server.json'))]);

        $server = $api->servers->get(3);

        $this->assertInstanceOf(Server::class, $server);
    }

    public function test_false_is_returned_if_no_server_was_found()
    {
        $api = $this->createClient([new Response(404, ['Content-Type' => 'text/html; charset=UTF-8'])]);

        $server = $api->servers->get(2);

        $this->assertFalse($server);
    }

    public function test_a_server_can_be_created()
    {
        $api = $this->createClient();

        $server = $this->createServer($api, 'Creation test');
        $server->delete();

        $this->assertInstanceOf(Server::class, $server);
        $this->assertEquals('Creation test', $server->name);
    }

    public function test_a_server_can_be_deleted()
    {
        $api = $this->createClient();

        $server = $this->createServer($api, 'Deletion test');

        $server = $api->servers->get($server->id);
        $this->assertTrue($server->delete());
    }
}