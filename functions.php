<?

require 'markdown.php';

date_default_timezone_set('America/Chicago');
$pageURL = $_SERVER["REQUEST_URI"];
$pageURL = explode('?', $pageURL);
$pageURL = array_shift($pageURL);
$pageURL = basename($pageURL); 
$pageURL = intval( $pageURL);
$unix = time();

// Process the ADD A notes form
if (isset($_POST['add'])) {
	$db = Core::getInstance();
	$db = $db->pdo;

	$text = trim($_POST['text']);
	$title = trim($_POST['title']);
	$category = trim( $_POST['category'] );

	$url = $unix;

	$sql = 'INSERT INTO notes SET
		text=:text,
		publishdate=:publishdate,
		updatedate=:updatedate,
		word_count = :word_count,
		title=:title';
	
	$s = $db->prepare($sql);
	$s->bindValue('text', $text);
	$s->bindValue('publishdate', $unix);
	$s->bindValue('updatedate', $unix);
	$s->bindValue('word_count', $_POST['word_count']);
	$s->bindValue('title', $title);
	$s->execute();
	$nid = $db->lastInsertId();

	if ( ! empty( $category ) )
	{
		$sql = 'SELECT * FROM categories WHERE name = :category';
		$s = $db->prepare($sql);
		$s->bindValue('category', $category);
		$s->execute();

		if ( $s->rowCount() > 0 )
		{
			$row = $s->fetch();
			$cid = $row['id'];
		}
		else
		{
			$sql = 'INSERT INTO categories SET name = :category';
			$s = $db->prepare($sql);
			$s->bindValue('category', $category);
			$s->execute();
			$cid = $db->lastInsertId();
		}
		$sql = 'INSERT INTO categories_lookup SET nid = :nid, cid = :cid';
		$s = $db->prepare($sql);
		$s->bindValue('nid', $nid);
		$s->bindValue('cid', $cid);
		$s->execute();
	}

	header('Location: '. View::url( $nid, $title ) );
	exit();
}

// Process the SAVE A notes form
else if (isset($_POST['save'])) {
	$db = Core::getInstance();
	$pdo = $db->pdo;

	$id = $_POST['id'];
	$text = trim($_POST['text']);
	$title = trim($_POST['title']);
	$category = trim( $_POST['category'] );

	$sql = 'UPDATE notes SET text = :text,
		updatedate = :updatedate,
		title = :title,
		word_count = :word_count
		WHERE id = :id';

	$s = $pdo->prepare($sql);
	$s->bindValue('text', $text);
	$s->bindValue('updatedate', $unix);
	$s->bindValue('title', $title);
	$s->bindValue('word_count', $_POST['word_count']);
	$s->bindValue('id', $id);
	$s->execute();


	if ( ! empty( $category ) )
	{
		$sql = 'SELECT * FROM categories WHERE name = :category';
		$s = $pdo->prepare($sql);
		$s->bindValue('category', $category);
		$s->execute();

		if ( $s->rowCount() > 0 )
		{
			$row = $s->fetch();
			$cid = $row['id'];
		}
		else
		{
			$sql = 'INSERT INTO categories SET name = :category';
			$s = $pdo->prepare($sql);
			$s->bindValue('category', $category);
			$s->execute();
			$cid = $pdo->lastInsertId();
		}
		$sql = 'DELETE FROM categories_lookup WHERE nid = :nid';
		$s = $pdo->prepare($sql);
		$s->bindValue('nid', $id);
		$s->execute();

		$sql = 'INSERT INTO categories_lookup SET nid = :nid, cid = :cid';
		$s = $pdo->prepare($sql);
		$s->bindValue('nid', $id);
		$s->bindValue('cid', $cid);
		$s->execute();
	}



	header("Location:" . View::url( $id, $title ) );
	exit();
}

// Process the DELETE A notes form
else if (isset($_GET['delete'])) {
	$db = Core::getInstance();
	$pdo = $db->pdo;

	$id = $_GET['delete'];
	$sql = "DELETE FROM notes WHERE id=:nid";
	$s = $pdo->prepare($sql);
	$s->bindValue('nid', $id);
	$s->execute();

	$sql = 'DELETE FROM categories_lookup WHERE nid = :nid';
	$s = $pdo->prepare($sql);
	$s->bindValue('nid', $id);
	$s->execute();

	header('Location: /');
	exit();
}

function get_category_dropdown()
{

	$db = Core::getInstance();
	$db = $db->pdo;
	$sql = 'SELECT DISTINCT C.name FROM `categories` C INNER JOIN categories_lookup CID ON C.id = CID.cid
ORDER BY C.name';
	$s = $db->query($sql);
	$rows = $s->fetchAll();

	$output = "";

	if ( ! empty( $rows ) )
	{
		$output = '<div class="select-category-wrap"><select name="select-category" style="width: 200px"><option value="">-- select --</option>';
		foreach ( $rows as $row )
		{
			$output .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
		}
		$output .= '</select></div>';
	}
	return $output;
}

