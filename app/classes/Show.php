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
	private $banner;
	
	public function __construct($show = array()){
		if(isset($show) && !empty($show['name']) && !empty($show['tvdbID'])){					$this->id = (int) $show['id'];
			$this->name = $show['name'];
			$this->url = $show['url'];
			$this->tvdbID = (int) $show['tvdbID'];
			$this->tvdb = $tvdb = new Client(TVDB_URL, TVDB_API_KEY);
			$banners = $tvdb->getBanners($this->tvdbID);
			$this->setBanner($banners[1]->thumbnailPath);
		}
	}
	
	public function all(){
		global $DB;
		$result = $DB->query('SELECT * from shows');
		return $result->fetchAll();
	}
	
	public function find($url){
		global $DB;
		$stmt = $DB->prepare('SELECT * from shows WHERE url = :url');
		$stmt->bindParam('url', $url, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getEpisodes($update = false){
		global $DB;
		$stmt = $DB->prepare('SELECT * from episodes WHERE showID = :showID');
		$stmt->bindParam('showID', $this->id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$episodes = array();
		$specials = array();
		if($result && !$update){
			foreach($result as $episode){
				if($episode['season'] == 0){
					array_push($specials, array(
						'showID' => (int) $this->id,
						'imdbID' => $episode['imdbID'],
						'name' => $episode['name'],
						'season' => $episode['season'],
						'number' => $episode['number'],
						'airDate' => (int) $episode['airDate']
					));
				}else{
					array_push($episodes, array(
						'showID' => (int) $this->id,
						'imdbID' => $episode['imdbID'],
						'name' => $episode['name'],
						'season' => $episode['season'],
						'number' => $episode['number'],
						'airDate' => (int) $episode['airDate']
					));
				}
			}
		}else{
			$serieEpisodes = $this->tvdb->getSerieEpisodes($this->tvdbID);
			$eps = $serieEpisodes['episodes'];
			foreach($eps as $episode){
				if(is_object($episode->firstAired)){
					$airDate = $episode->firstAired->getTimeStamp();
				}else{
					$airDate = 0;
				}
				$stmt = $DB->prepare('INSERT into episodes SET showID = :showID, imdbID = :imdbID, name = :name, season = :season, number = :number, airDate = :airDate, watched = 0');
				$stmt->bindParam('showID', $this->id, PDO::PARAM_INT);
				$stmt->bindParam('imdbID', $episode->imdbId, PDO::PARAM_STR);
				$stmt->bindParam('name', $episode->name, PDO::PARAM_STR);
				$stmt->bindParam('season', $episode->season, PDO::PARAM_INT);
				$stmt->bindParam('number', $episode->number, PDO::PARAM_INT);
				$stmt->bindParam('airDate', $airDate, PDO::PARAM_INT);
				$stmt->execute();
				if($episode->season == 0){
					array_push($specials, array(
						'showID' => (int) $this->id,
						'imdbID' => $episode->imdbId,
						'name' => $episode->name,
						'season' => $episode->season,
						'number' => $episode->number,
						'airDate' => (int) $airDate
					));
				}else{
					array_push($episodes, array(
						'showID' => (int) $this->id,
						'imdbID' => $episode->imdbId,
						'name' => $episode->name,
						'season' => $episode->season,
						'number' => $episode->number,
						'airDate' => (int) $airDate
					));
				}
			}
		}
		return array(
			'specials' => $specials,
			'episodes' => $episodes
		);
	}
	
	private function setBanner($banner){
		$this->banner = $banner;
	}
}