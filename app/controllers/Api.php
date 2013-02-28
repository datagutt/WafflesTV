<?php
require TOP_DIR . 'config.php';
require APP_DIR . 'classes/Provider.php';
$providers = $config['providers'];
foreach($providers as $provider){
	require APP_DIR . 'classes/providers/' . $provider . '.php';
}
class APIController extends JSONController{
	public function index(){
		echo '{}';
	}
	
	public function download($show  = '', $season = 0, $episode = 1){
		global $config;
		$providers = $config['providers'];
		// ?
		foreach($providers as $provider){
			$p = new $provider;
			if(!empty($show)){
				$file = $p->getTorrent($show, $season, $episode);
				if($file){
					echo json_encode(
						array(
							'type' => 'OK',
							'message' => 'File downloaded'
						)
					);
					break;
				}else{
					echo json_encode(
						array(
							'type' => 'ERR',
							'message' => 'Can\'t find episode!'
						)
					);
				}
			}else{
				echo json_encode(
					array(
						'type' => 'ERR',
						'message' => 'No show provided'
					)
				);
			}
		}
	}
}