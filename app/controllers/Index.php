<?php
require APP_DIR . 'classes/Show.php';
class IndexController extends Controller{
	public function index(){
		$all = id(new Show)->all();
		$shows = array();
		foreach($all as $show){
			$s = new Show($show);
			array_push($shows, $s);
		}
		//$result = $p->getTorrent('The Simpsons', '24', '12');
		echo $this->view->render('shows.html', array(
			'shows' => $shows
		));
	}
}