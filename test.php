<?php

$a=3; $b=4;

if($a||$b=5){
    echo 'tudo';
}

echo $b;

class user {

    public function __toString()
    {
        return '';
    }
}

function temp(String $name){
    echo $name;
}

temp(new user());


