<?

require 'markdown.php';

date_default_timezone_set('America/Chicago');
$pageURL = $_SERVER["REQUEST_URI"];
$pageURL = explode('?', $pageURL);
$pageURL = array_shift($pageURL);
$pageURL = basename($pageURL); 

$unix = time();

// Process the ADD A SECRET form
if (isset($_POST['add'])) {
	$db = Core::getInstance();
	$db = $db->pdo;

	$content = $_POST['secrettext'];
	$title = $_POST['title'];

	$url = $unix;

	$sql = 'INSERT INTO secret SET
		secrettext=:secrettext,
		publishdate=:publishdate,
		updatedate=:updatedate,
		word_count = :word_count,
		title=:title';
	
	$s = $db->prepare($sql);
	$s->bindValue('secrettext', $content);
	$s->bindValue('publishdate', $unix);
	$s->bindValue('updatedate', $unix);
	$s->bindValue('word_count', $_POST['word_count']);
	$s->bindValue('title', $title);
	$s->execute();
	header('Location: '. $db->lastInsertId());
	exit();
}

// Process the SAVE A SECRET form
else if (isset($_POST['save'])) {
	$db = Core::getInstance();
	$pdo = $db->pdo;

	$id = $_POST['id'];
	$text = $_POST['secrettext'];
	$title = $_POST['title'];
	$url = $_POST['url'];
	
	$sql = 'UPDATE secret SET secrettext = :text,
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
	header("Location:" . $id);
	exit();
}

// Process the DELETE A SECRET form
else if (isset($_GET['delete'])) {
	$db = Core::getInstance();
	$db = $db->pdo;

	$id = $_GET['delete'];
	$sql = "DELETE FROM secret WHERE id='$id'";

	$db->exec($sql);

	header('Location: /');
	exit();
}

