<?php
require TOP_DIR . 'vendor/Twig/Autoloader.php';
class Twig_N1_Environment extends Twig_Environment{
	public function render($template, array $vars = array()){
		try{
			return parent::render($template, $vars);
		}catch(Twig_Error_Loader $e) {
			echo '<h2>'.$e->getRawMessage().'</h2>';
		}
	}
}
function loadTwig(){
	$loader = new Twig_Loader_Filesystem(APP_DIR . '/views/');
	$twig = new Twig_N1_Environment($loader, array(
		'cache' => false
	));
	return $twig;
}