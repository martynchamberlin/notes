<?php

require'initialize.php';

// This query is strictly for paginating purposes.
$args = array(
	'columns' => 'count(id)',
	'where' => 'WHERE secrettext LIKE "%'. stripslashes(urldecode($_GET['q'])) . '%" || title LIKE "%' . stripslashes(urldecode($_GET['q'])) . '%"',
);

$result = new Query( $args );
$result = $result->data;

$count = $result[0][0];
$paginate = View::paginate( $count );


$args = array(
	'where' => 'WHERE secrettext LIKE "%'. stripslashes(urldecode($_GET['q'])) . '%" || title LIKE "%' . stripslashes(urldecode($_GET['q'])) . '%"',
	'orderby' => 'updatedate',
	'order' => 'DESC',
	'offset' => View::$startAt,
	'posts_per_page' => View::$perPage

);

$posts = new Query( $args );

require 'top-main.php';

echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';

