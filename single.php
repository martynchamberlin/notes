<?

require_once 'initialize.php';

require 'top-main.php';


	$db = Core::getInstance();
	$db = $db->pdo;

	$query = "SELECT *, N.id as nid, C.name as cat_name FROM notes N LEFT JOIN categories_lookup L on N.id = L.nid LEFT JOIN categories C on L.cid = C.id WHERE N.id=$pageURL";
	$s = $db->query($query);
	$post = $s->fetch();

?>


<div class="single">

<? echo View::formatSingle( $post ); ?>

</div></div>
 <?php
 require('footer.php');
