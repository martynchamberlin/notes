<? 

function __autoload($className)
{
	require_once 'class/' . strtolower($className) . '.php';
}

require_once 'functions.php';
require_once 'header.php';



