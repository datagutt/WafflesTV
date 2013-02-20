<?php
class Provider{
	public $url;
	public $titleRegex = '/S(\d+)E(\d+)/i';
	public$withZero = 1;
	
	public function fixTitle($title){
		return $title;
	}
	
	public function getUrl($data){
		return $data['url'];
	}
	
	public function getTorrent($title, $season, $episode){
		if($this->withZero){
			$season = '0' . $season;
			$episode = '0' . $episode;
		}
		$needed = 'S' . $season . 'E' . $episode;
		$content = file_get_contents(sprintf($this->url, $this->fixTitle($title), $needed));  
		$x = new SimpleXmlElement($content);  
		foreach($x->channel->item as $item){
			$parsed = $this->parse($item);
			if(is_array($parsed) && $parsed['season'] == $season && $parsed['episode'] == $episode){
				echo 'match: ' . $needed . ' == ' . $parsed['season'].$parsed['episode'];
				$this->download($parsed['url'], $parsed['file']);
				return true;
			}
		}
		return false;
	}
	
	private function download($url, $file){
		require TOP_DIR . 'config.php';
		$torrentDir = $config['dirs']['torrents'];
		if(file_exists($torrentDir) && !empty($url)){
			$content = file_get_contents($url);
			file_put_contents($torrentDir . '/' . $file, $content);
		}
	}
	
	public function parse($data){
		preg_match($this->titleRegex, $data->title, $title);
		if(is_array($title) && isset($title[1]) && isset($title[2])){
			$season = $title[1];
			$episode = $title[2];
			$url = $this->getUrl($data);
			preg_match('/[^\/]+$/', $url, $matches);
			$file = isset($matches[0]) ? $matches[0] : 'tmp.torrent';
			return array(
				'season' => $season,
				'episode' => $episode,
				'file' => urldecode($file),
				'url' => $url
			);
		}
		return false;
	}
}