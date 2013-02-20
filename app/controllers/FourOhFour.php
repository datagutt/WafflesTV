<?php
class FourOhFourController extends Controller{
	public function index(){
		header('Status: 404 Not Found', true);
		echo $this->view->render('404.html');
	}
}