<?php

require'initialize.php';

// This query is strictly for paginating purposes.

// are they searching a specific category?
if ( strpos( $_GET['q'], 'in:' ) === 0 )
{
	$db = Core::getInstance();
	$pdo = $db->pdo;
	$sql = 'SELECT count(*), N.id as nid FROM categories C INNER JOIN categories_lookup L ON C.id = L.cid INNER JOIN notes N on L.nid = N.id WHERE C.name = :name';
	$s = $pdo->prepare($sql);
	$s->bindValue('name',stripslashes(urldecode( substr( $_GET['q'], 3) ) ) );
	$s->execute();
	$result = $s->fetchAll();
}
// else they are doing a specific search
else
{
$args = array(
	'columns' => 'count(Notes.id)',
	'where' => 'WHERE text LIKE "%'. stripslashes(urldecode($_GET['q'])) . '%" || title LIKE "%' . stripslashes(urldecode($_GET['q'])) . '%"',
);

$result = new Query( $args );
$result = $result->data;
}
$count = $result[0][0];
$paginate = View::paginate( $count );

if ( strpos( $_GET['q'], 'in:' ) === 0 )
{
	$db = Core::getInstance();
	$pdo = $db->pdo;
	$sql = 'SELECT *, N.id as nid FROM categories C INNER JOIN categories_lookup L ON C.id = L.cid INNER JOIN notes N on L.nid = N.id  WHERE C.name = :name
	ORDER BY updatedate DESC LIMIT ' . View::$startAt . ', ' . View::$perPage;
	$s = $pdo->prepare($sql);
	$s->bindValue('name',stripslashes(urldecode( substr( $_GET['q'], 3) ) ) );
	$s->execute();
	$posts = new Query();
	$posts->data = $s->fetchAll();
}
else
{
$args = array(
	'where' => 'WHERE text LIKE "%'. stripslashes(urldecode($_GET['q'])) . '%" || title LIKE "%' . stripslashes(urldecode($_GET['q'])) . '%"',
	'orderby' => 'updatedate',
	'order' => 'DESC',
	'offset' => View::$startAt,
	'posts_per_page' => View::$perPage

);

$posts = new Query( $args );
}


require 'top-main.php';

echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';

