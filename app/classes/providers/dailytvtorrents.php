<?php
class DailyTVTorrents extends Provider{
	public $url = 'http://www.dailytvtorrents.org/rss/show/%s';
	
	public function fixTitle($title){
		return strtolower(str_replace(' ', '-', $title));
	}
	
	public function getUrl($data){
		if(isset($data) && isset($data->description)){
			$description = (string)$data->description;
			preg_match('/<a href="(.+)">/', $description, $matches);
			return $matches[1];
		}
		return false;
	}
}