<? 

function __autoload($className)
{
	require 'class/' . strtolower($className) . '.php';
}

require'functions.php';
require'header.php';



