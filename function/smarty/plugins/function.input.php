<?php

function smarty_function_input($args, $smarty) {
	return '<input name = "'. $args['name'] .'" widht = "'. $args['width'] .'" value = "'. $args['value'] .'"  >';
}





?>