<?php

require'initialize.php';

// check if they want to search in archived stuff too
$all = false;
$q = $_GET['q'];
if ( substr(  $q , strlen( $q ) - 3 ) == ' -a' )
{
	$q = substr( $q, 0, strlen( $q ) - 3 );
	$all = true;
}


// This query is strictly for paginating purposes.

// are they searching a specific category?
if ( strpos( $q, 'in:' ) === 0 )
{
	$db = Core::getInstance();
	$pdo = $db->pdo;
	$sql = 'SELECT count(*), N.id as nid FROM categories C INNER JOIN categories_lookup L ON C.id = L.cid INNER JOIN notes N on L.nid = N.id WHERE C.name = :name';
	if ( ! $all ) 
	{
		$sql .= ' AND archived=0';
	}

	$s = $pdo->prepare($sql);
	$s->bindValue('name',stripslashes(urldecode( substr( $q, 3) ) ) );
	$s->execute();
	$result = $s->fetchAll();
}
// else they are doing a specific search
else
{
$args = array(
	'columns' => 'count(Notes.id)',
	'where' => 'WHERE text LIKE "%'. stripslashes(urldecode($q)) . '%" || title LIKE "%' . stripslashes(urldecode($q)) . '%"',
);
	if ( ! $all ) 
	{
		$args['where'] .= ' AND archived=0';
	}

$result = new Query( $args );
$result = $result->data;
}
$count = $result[0][0];
$paginate = View::paginate( $count );

if ( strpos( $q, 'in:' ) === 0 )
{
	$db = Core::getInstance();
	$pdo = $db->pdo;
	$sql = 'SELECT *, N.id as nid FROM categories C INNER JOIN categories_lookup L ON C.id = L.cid INNER JOIN notes N on L.nid = N.id  WHERE C.name = :name';
		if ( ! $all ) 
	{
		$sql .= ' AND archived=0';
	}
	$sql .= ' ORDER BY updatedate DESC LIMIT ' . View::$startAt . ', ' . View::$perPage;
	$s = $pdo->prepare($sql);
	$s->bindValue('name',stripslashes(urldecode( substr( $q, 3) ) ) );
	$s->execute();
	$posts = new Query();
	$posts->data = $s->fetchAll();
}
else
{
$args = array(
	'where' => 'WHERE text LIKE "%'. stripslashes(urldecode($q)) . '%" || title LIKE "%' . stripslashes(urldecode($q)) . '%"',
	'orderby' => 'updatedate',
	'order' => 'DESC',
	'offset' => View::$startAt,
	'posts_per_page' => View::$perPage

);

	if ( ! $all ) 
	{
		$args['where'] .= ' AND archived=0';
	}


$posts = new Query( $args );
}


require 'top-main.php';

echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';

