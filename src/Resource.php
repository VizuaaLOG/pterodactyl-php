<?php

namespace VizuaaLOG\Pterodactyl;

class Resource {
    /**
     * Create a new resource instance.
     *
     * @param  array $attributes
     * @param  Pterodactyl $pterodactyl
     * @return void
     */
    public function __construct($attributes = [], $pterodactyl = null)
    {
        $this->pterodactyl = $pterodactyl;
        $this->fill($attributes);
    }

    /**
     * Fill the resource with the array of attributes.
     *
     * @return void
     */
    private function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            $key = $this->camelCase($key);
            $this->{$key} = $value;
        }
    }

    /**
     * Convert the key name to camel case.
     *
     * @param $key
     */
    private function camelCase($key)
    {
        $parts = explode('_', $key);
        foreach ($parts as $i => $part) {
            if ($i !== 0) {
                $parts[$i] = ucfirst($part);
            }
        }
        return str_replace(' ', '', implode(' ', $parts));
    }
}