<?php

// This query is strictly for paginating purposes.
$args = array(
	'columns' => 'count(notes.id)',
	'where' => 'where archived=0'
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
	'where' => 'where archived=0'
);

$posts = new Query( $args );

require 'top-main.php';

echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';

