<?php
include TOP_DIR . 'vendor/TvDb/CurlException.php';
include TOP_DIR . 'vendor/TvDb/Client.php';
include TOP_DIR . 'vendor/TvDb/Serie.php';
include TOP_DIR . 'vendor/TvDb/Banner.php';
include TOP_DIR . 'vendor/TvDb/Episode.php';
use TvDb\Client;

class Show{
	public $name;
	public $tvdbID;
	private $episodes = array();
	
	public function __construct($name = '', $tvdbID = 0){
		if(!empty($name) && !empty($tvdbID)){
			$this->name = $name;
			$this->tvdbID = $tvdbID;
			$tvdb = new Client(TVDB_URL, TVDB_API_KEY);
			//var_dump($tvdb->getSeries($name));
		}
	}
	
	public function all(){
		global $DB;
		$result = $DB->query('SELECT * from shows');
		return $result->fetchAll();
	}
}