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
	
	public function update($show = ''){
		$show = new Show(id(new Show)->find($show));
		if(!empty($show->name)){
			$show->update();
			header('Location: /show/url/' . $show->url);
		}
	}
}
