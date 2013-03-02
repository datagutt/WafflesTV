<?php
require TOP_DIR . 'config.php';
require APP_DIR . 'classes/Show.php';
class ShowController extends Controller{
	
	public function index(){
		header('Location: /');
	}
	
	public function url($show = ''){
		$show = new Show(id(new Show)->find($show));
		//$result = $p->getTorrent('The Simpsons', '24', '12');
		if(!empty($show->name)){
			$episodes = $show->getEpisodes();
			echo $this->view->render('show.html', array(
				'show' => $show,
				'specials' => $episodes['specials'],
				'episodes' => $episodes['episodes']
			));
		}else{
			echo '<h1>Show does not exist!</h1>';
		}
	}
	
	public function stream($show = '', $season = 0, $episode = 1){
		global $config;
		header('Content-Disposition: attachment; filename="stream.xspf"');
		$show = new Show(id(new Show)->find($show));
		$output = '';
		if($season < 10 && $season > 0){
			$season = '0' . $season;
		}
		if($episode < 10){
			$episode = '0' . $episode;
		}
		if(!empty($show->name)){
			$output .= '<?xml version="1.0" encoding="utf-8"?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
<title>WafflesTV</title>
<info></info>
<trackList>';
			$files = array();
			$dir = $config['dirs']['shows'] . '/' . $show->name;
			$ritit = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
			
			if($season && $episode){
				foreach($ritit as $splFileInfo){
					$fileName = $splFileInfo->getRealPath();
					preg_match('/S(\d+)E(\d+)/i', $fileName, $matches);
					if(isset($matches[1]) && isset($matches[2])){
						if($matches[1] == $season && $matches[2] == $episode){
							array_push($files, $fileName);
						}
					}
				}
			}else{
				foreach($ritit as $splFileInfo){
					$fileName = $splFileInfo->getRealPath();
					preg_match('/S(\d+)E(\d+)/i', $fileName, $matches);
					if(isset($matches[1]) && isset($matches[2])){
						array_push($files, $fileName);
					}
				}
			}
			
			// Randomize
			shuffle($files);
			
			foreach($files as $file){
				if(!empty($file) && file_exists($file)){
					preg_match('/S(\d+)E(\d+)/i', $file, $matches);
					if(isset($matches[1])){
						$season = $matches[1];
					}else{
						$season = '?';
					}
					if(isset($matches[2])){
						$episode = $matches[2];
					}else{
						$episode = '?';
					}
					$output .= '
		<track>
			<creator></creator>
			<title>' . $show->name . ' S' . $season . 'E' . $episode . '</title>
			<location>file://' . $file . '</location>
			<annotation></annotation>
			<info>Streaming ' . $show->name .'</info>
		</track>';
				}
			}
			$output .= '
</trackList>
</playlist>';
		}
		echo $output;
	}
	
	public function update($show = ''){
		$show = new Show(id(new Show)->find($show));
		if(!empty($show->name)){
			$show->update();
			header('Location: /show/url/' . $show->url);
		}
	}
}
