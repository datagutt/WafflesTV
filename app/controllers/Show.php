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
		$show = new Show(id(new Show)->find($show));
		$output = '';
		if($season < 10 && $season > 0){
			$season = '0' . $season;
		}
		if($episode < 10){
			$episode = '0' . $episode;
		}
		if(!empty($show->name)){
			$files = array();
			$dir = $config['dirs']['shows'] . '/' . $show->name;
			$ritit = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
			
			if($season && $episode){
				foreach($ritit as $splFileInfo){
					$fileName = $splFileInfo->getRealPath();
					preg_match('/S(\d+)E(\d+)/i', $fileName, $matches);
					if(isset($matches[1]) && isset($matches[2])){
						if($matches[1] == $season && $matches[2] == $episode){
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename="'.basename($fileName). '"');
							header('Content-Transfer-Encoding: binary');
							header('Expires: 0');
							header('Cache-Control: must-revalidate');
							header('Pragma: public');
							header('Content-Length: ' . filesize($fileName));
								
							ob_end_flush();
							readfile($fileName);
							exit;
						}
					}
				}
			}
		}
	}
	
	public function update($show = ''){
		$show = new Show(id(new Show)->find($show));
		if(!empty($show->name)){
			$show->update();
			header('Location: /show/url/' . $show->url);
		}
	}
}
