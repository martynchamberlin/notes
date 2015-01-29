<? 

require_once 'class/config.php';

require_once 'initialize.php';

$page = View::showPage( false );
if (empty($page) )
{
	require('home.php');
}

else 
{
	require('single.php');
}
