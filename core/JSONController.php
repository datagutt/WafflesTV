<?php
class JSONController extends Controller{
	public function __construct($view = NULL){
		parent::__construct($view);
		header('Content-type: application/json');
	}
}