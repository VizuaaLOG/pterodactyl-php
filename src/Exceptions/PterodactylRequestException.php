<?php

namespace VizuaaLOG\Pterodactyl\Exceptions;

class PterodactylRequestException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}