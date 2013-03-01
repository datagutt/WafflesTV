<?php
function isAired($ref){
	$now = id(new DateTime())->getTimestamp();
	return $ref && $now >= $ref;
}