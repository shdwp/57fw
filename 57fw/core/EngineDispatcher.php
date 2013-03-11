<?php
namespace Core;

/** 
 * Interface for dispatchers
 */
abstract class EngineDispatcher {
    protected $config; 
//    protected $e;

    public function __construct($config=array()) {
        $this->config = $config;
    }

    /**
     * @param \Core\Engine
     */
    public abstract function engage($e);

    /**
     * @param mixed
     * @return mixed
     */
    public function getConfig($k) {
        return $this->config[$k];
    }

/*
    public function setEngine($e) {
        $this->e = $e;
    }
*/

}
