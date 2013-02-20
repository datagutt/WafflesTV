<?php
spl_autoload_register(function($class){
	// If it is trying to load a Twig class, use Twigs autoloader instead.
	if(strpos($class, 'Twig') !== false){
		Twig_Autoloader::autoload($class);
		return;
	}else if(strpos($class, 'Controller') == false){
		return;
	}
	$class = str_replace('Controller', '', $class);
	
	if(file_exists(APP_DIR . 'controllers/' . ucfirst($class) . '.php')){
		require APP_DIR . 'controllers/' . ucfirst($class) . '.php';
	}else{
		require APP_DIR . 'controllers/FourOhFour.php';
	}
});
class Front{
	public $_controller, $_method;
	
	public function __construct($view){
		global $config;
		$this->view = $view;
		foreach($config['functions'] as $name => $func){
			$this->view->addFunction($name, new Twig_Function_Function($func));
		}
	}
	
	public function run($route = NULL){
		global $config;
		
		if(is_null($route)){
			if(isset($_SERVER[$config['url_variable']])){
				$route = $_SERVER[$config['url_variable']];
			}else{
				throw new Exception('URL variable not supported');
			}
		}
		$request = explode('/', trim($route, '/'));
        
		if(!empty($request[0])){
			$this->_controller = $request[0];
		}else{
			$this->_controller = 'index';
		}
		array_shift($request);
		
		if(!empty($request[0])){
			$this->_method = $request[0];
		}else{
			$this->_method = 'index';
		}
		array_shift($request);
		
		$className = ucfirst($this->_controller) . 'Controller';
		if(!class_exists($className)){
			$className = 'FourOhFourController';
		}
		
		$controller = new $className($this->view);
		$method = $this->_method;
		if(method_exists($className, $method) && is_callable(array($className, $method))){
			call_user_func_array(array($controller, $method), $request);
		}else{
			$controller->index();
		}
	}
}