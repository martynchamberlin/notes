<?php

function __autoload($className)
{
	require 'class/' . strtolower($className) . '.php';
}

require'functions.php';

if ( isset( $_GET['aid'] ) )
{
	$core = Core::getInstance();
	$sql = 'update notes set archived=1 where id = :id';
	$s = $core->pdo;
	$s = $s->prepare( $sql );
	$s->bindValue('id', $_GET['aid'] );
	$s->execute();
	header("location: /archive");
	exit;
}
else if ( isset( $_GET['naid'] ) )
{
	$core = Core::getInstance();
	$sql = 'update notes set archived=0 where id = :id';
	$s = $core->pdo;
	$s = $s->prepare( $sql );
	$s->bindValue('id', $_GET['naid'] );
	$s->execute();
	header("location: /");
	exit;
}



require 'header.php';

// This query is strictly for paginating purposes.
$args = array(
	'columns' => 'count(notes.id)',
	'where' => 'where archived=1'

);

$result = new Query( $args );
$result = $result->data;

$count = $result[0][0];
$paginate = View::paginate( $count );

$args = array(
	'orderby' => 'updatedate',
	'order' => 'desc',
	'offset' => View::$startAt,
	'posts_per_page' => View::$perPage,
	'where' => 'where archived="1"'
);

$posts = new Query( $args );

require 'top-main.php';

echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';


