<?php

function smarty_modifier_cut($var) {
	$cut_str = $var;
	
	$num = mb_strlen($var, 'utf-8');
	if ($num > 10) {
		$cut_str = mb_substr($var, 0, 10, 'utf-8') . "...";
	}
	return $cut_str;
}




?>