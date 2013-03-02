<?php
function isAired($ref){
	$now = id(new DateTime())->getTimestamp();
	return $ref && $now >= $ref;
}
function getGitRev(){
	return exec('git rev-parse --short HEAD');
}