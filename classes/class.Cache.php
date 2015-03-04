<?php
class Cache {
	var $url;
	var $cache_file;
	var $cache_folder		= "_cache";
	var $timeout			= 1800; //30*60;

	function Cache($timeout=30,$cache_folder="_cache"){
		$this->timeout=$timeout*60;
		$this->cache_folder=$cache_folder;
	}

	function is_cached(){
		$cachefile = $this->cache_folder . "/" .  $this->cache_file;
		if(!file_exists($cachefile)) return false;

		//clearstatcache() ;
		//echo time()." - ".filemtime($cachefile);

		if(((time() - filemtime($cachefile)) < ($this->timeout))) return true;

		return false;
	}
}