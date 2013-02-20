<?php
require TOP_DIR . 'config.php';
require APP_DIR . 'classes/Provider.php';
$providers = $config['providers'];
foreach($providers as $provider){
	require APP_DIR . 'classes/providers/' . $provider . '.php';
}
require APP_DIR . 'classes/Show.php';
class IndexController extends Controller{
	public function index(){
		$p = new DailyTvTorrents();
		//$result = $p->getTorrent('The Simpsons', '24', '12');
		echo $this->view->render('shows.html');
	}
}