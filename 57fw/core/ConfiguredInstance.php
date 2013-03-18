<?php
namespace Core;

/**
 * Handles configuration of apps
 */
class ConfiguredInstance {
    protected $config;

    /**
     * @param array
     */
    public function __construct($config=array()) {
        $this->config = $config;
    }
    
    /**
     * Get config value or null
     * @param mixed
     * @return mixed
     */
    public function config($k) {
        if (isset($this->config[$k]))
            return $this->config[$k];
    }
}