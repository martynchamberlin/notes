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
?>


<div class="home">

<div class="top">
<p><a href="/">Cancel&nbsp;</a><a href="/add">&nbsp;Add Note</a></p>

<form class="search_form" action="/search" method="GET">
<input type="search" name="q" placeholder="Search the database ...">
</form>
</div>

<? echo View::formatArchive( $posts ); ?>	

<div class="paginate">

<?= $paginate; ?>

</div></div></div><?php

require 'footer.php';

