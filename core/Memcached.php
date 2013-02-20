<?php
class Memcached{
	private $config;
	public function __construct($servers){
		$this->m = false;	
		if(class_exists('Memcache')){
			$this->m = new Memcache;
			$this->connect($servers);
		}
	}
	private function connect($servers){
		foreach($servers as $server){
			if($this->addServer($server)){
				return true;
			}else{
				return false;
			}
		}
	}
	public function addServer($server){
		return $this->m->addServer($server['host'], $server['port']);
	}
	public function get($key) {
		return ($this->m) ? $this->m->get($key) : false;
	}
	public function set($key,$object,$timeout = 60) {
		return ($this->m) ? $this->m->set($key, $object, MEMCACHE_COMPRESSED,$timeout) : false;
	}
	public function replace($key,$object,$timeout = 60) {
		return ($this->m) ? $this->m->replace($key, $object, MEMCACHE_COMPRESSED, $timeout) : false;
	}
	public function delete($key) {
		return ($this->m) ? $this->m->delete($key) : false;
	}
	public function getVersion(){
		return ($this->m) ? $this->m->getVersion() : false;
	}
}