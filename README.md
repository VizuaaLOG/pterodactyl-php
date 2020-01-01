# Pterodactyl PHP
Pterodactyl PHP is an unofficial PHP API client for the Pterodactyl game panel. It offers an object orientated approach with a 'smarter' may of performing updates. This package was primarily written for the needs of a project, but I have tried to keep it as generic as I can to offer flexibility for others to use it as well.

**This has been tested with Pterodactyl 0.7.16**

The code has been documented so any good IDE should be able to help with autocompletion.

## Installation
Installation is very easy thanks to composer. Simply run `composer install vizuaalog/pterodactyl-php`.

## Usage
This assumes you have setup composer within your project to allow for the `use` statements.

Everything starts with the primary `Pterodactyl` class:

```php
use VizuaaLOG\Pterodactyl\Pterodactyl;

$panel = new Pterodactyl($api_key, $base_uri, $type);
```

+ `$api_key` (string) - This should be the API key generated either via the admin section of the panel or the user section
+ `$base_uri` (string) - This is the base uri of your panel. E.g. 'https://pterodactyl.app'
+ `$type` (string, default `application`) - This is the type of API routes you would like to use. If you provide an API key from the user section, then set this to `client` otherwise the default `application` is fine.

All of the values provided to a create or update method follow the exact requirements of the Pterodactyl API and it's advised to look at the documentation for this.

Update methods called directly on a resource will help where possible and prefill any required fields that you do not pass. E.g. only wanting to update the name of the server - Pterodactyl requires more than just the name be provided in the request, so this is done behind the scenes.

Some methods have two different variants, the first is if you already have the resource object, helps with readability. The second is if you only have an id and do not want to get the resource details first.

### Servers
Below are the methods available for managing servers.
```php
// Returns an array of Server objects.
$servers = $panel->servers->all();

// Returns a single Server object
// If using the client mode, then this should be the string identifier instead
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$server = $panel->servers->get(1, $includes);

// Returns a single Server object
$server = $panel->servers->getByExternalId('external_id', $includes);

// Returns a single Server object if successful, throws exception on error
$server = $panel->servers->create($data);

// Update a server's details
// Returns the updated Server object
$server->update($data);
$panel->servers->update($server_id, $data);

// Update a server's build configuration
// Returns the updated Server object
$server->updateBuild($data);
$panel->servers->updateBuild($server_id, $data);

// Update a server's startup configuration
// Returns the updated server object
$server->updateStartup($data);
$panel->servers->updateStartup($server_id, $data);

// Suspend a server
// Returns true if successful, false otherwise
$server->suspend();
$panel->servers->suspend($server_id);

// Unsuspend a server
// Returns true if successful, false otherwise
$server->unsuspend();
$panel->servers->unsuspend($server_id);

// Reinstall a server
// Returns true if successful, false otherwise
$server->reinstall();
$panel->servers->reinstall($server_id);

// Rebuild a server
// Returns true is successful, false otherwise
$server->rebuild();
$panel->servers->rebuild($server_id);

// Delete a server
// Returns true if successful, false otherwise
$server->delete($force);
$panel->servers->delete($server_id, $force);

// Get all databases for a server
// Returns an array of ServerDatabase objects
$server->databases();
$panel->servers->databases($server_id);

// Get a database for a server
// Returns a single ServerDatabase object
$server->database($database_id);
$panel->servers->database($server_id, $database_id);

// Create a database for a server
// Returns a single ServerDatabase object
$server->createDatabase($data);
$panel->servers->createDatabase($server_id, $data);

// Reset a database's password
// Returns true on success, false otherwise
$server->resetDatabasePassword($database_id);
$panel->servers->resetDatabasePassword($server_id, $database_id);

// Delete a database
// Returns true on success, false otherwise
$server->deleteDatabase($database_id);
$panel->servers->deleteDatabase($database_id);
```

### User
Below are the methods available for managing users.
```php
// Get all users
// Returns an array of User objects
$users = $panel->users->all();

// Get a single user
// Returns a single User object
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$user = $panel->users->get(1, $includes);

// Get a single user by external id
// Returns a single User object
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$user = $panel->users->getByExternalId(1, $includes);

// Create a user
// Returns the created User object
$user = $panel->users->create($data);

// Edit a user
// Returns the updated User object
$user->update($data);
$panel->users->update($user_id, $data);

// Delete a user
// Returns true on success, false otherwise
$user->delete();
$panel->users->delete($user_id);
```

### Nodes
Below are the methods available for managing nodes.
```php
// Get all nodes
// Returns an array of Node objects
$nodes = $panel->nodes->all();

// Get a single node
// Returns a single Node object
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$node = $panel->nodes->get(1, $includes);

// Create a node
// Returns the created Node object
$node = $panel->nodes->create($data);

// Edit a node
// Returns the updated Node object
$node->update($data);
$panel->nodes->update($node_id, $data);

// Delete a node
// Returns true on success, false otherwise
$node->delete();
$panel->nodes->delete($node_id);

// Get a node's allocations
// Returns an array of Allocation objects
$allocations = $node->allocations();
$allocations = $panel->nodes->allocations($node_id);

// Create a new allocation
// Returns the newly created Allocation object
$allocation = $node->createAllocation($data);
$allocation = $panel->nodes->createAllocation($node_id, $data);

// Delete an allocation
// Returns true on success, false otherwise
$node->deleteAllocation($allocation_id);
$panel->nodes->deleteAllocation($node_id, $allocation_id);
```

### Locations
Below are the methods available for managing locations.
```php
// Get all locations
// Returns an array of Node objects
$locations = $panel->locations->all();

// Get a single location
// Returns a single Location object
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$location = $panel->locations->get(1, $includes);

// Create a location
// Returns the created Location object
$location = $panel->locations->create($data);

// Edit a location
// Returns the updated Location object
$location->update($data);
$panel->locations->update($location_id, $data);

// Delete a location
// Returns true on success, false otherwise
$location->delete();
$panel->locations->delete($location_id);
```

### Nests/Eggs
Below are the methods available for managing nests and eggs.
```php
// Get all nests
// Returns an array of Nest objects
$nests = $panel->nests->all();

// Get a single nest
// Returns a single Nest object
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$nest = $panel->nests->get(1, $includes);

// Get all eggs for a nest
// Returns an array of Egg objects
$eggs = $nest->eggs();
$nests = $panel->nests->eggs($nest_id);

// Get a single egg from a nest
// Returns a single Egg object
// $includes is an array of strings representing additional data to include in the same request, see Pterodactyl API docs for these.
$egg = $nest->egg($egg_id, $includes);
$egg = $panel->nests->egg(1, $includes);
```

### Exceptions
+ Providing no API key to the Pterodactyl constructor will throw an InvalidApiKeyException.
+ Providing no base uri to the Pterodactyl constructor will throw an InvalidBaseUriException.
+ If Pterodactyl returns an exception error a PterodactylRequestException is thrown for most methods, this exception will contain some of the information sent from the panel to help diagnose the issue.
+ In some cases a GuzzelException, ClientException or ServerException may be thrown which will indicate an issue communicating with the panel. In most cases these are translated to a PterodactylRequestException so shouldn't happen too often.