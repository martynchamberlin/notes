<?php

// This query is strictly for paginating purposes.
$args = array(
	'columns' => 'count(id)',
);

$result = new Query( $args );
$result = $result->data;

$count = $result[0][0];
$paginate = View::paginate( $count );

$args = array(
	'orderby' => 'updatedate',
	'order' => 'desc',
	'offset' => View::$startAt,
	'posts_per_page' => View::$perPage
);

$posts = new Query( $args );
?>


<div class="home">

<div class="top">
<a href="/add/" class="add-link">New Note</a>

<form class="search_form" action="search" method="GET">
<input type="search" name="q" placeholder="Search the database ...">
</form>
</div>

<? echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';

