<?php

require'initialize.php';

require 'top-main.php';

?><div class="edit"><?php

	$db = Core::getInstance();
	$db = $db->pdo;

	$id = $_GET['id'];
	$query = "SELECT * FROM secret WHERE id=$id";
	$result = $db->query($query);

	foreach ($result as $secret): ?>
		<form id="form" action="/?savesecret" method="post">
			<input class="input_title" autocomplete="off" type="text" name="title" placeholder="title" value="<?php echo $secret['title'] ?>"/>
			<input type="hidden" name="url" placeholder="permalink" value="<?php echo $secret['url'] ?>"/>
		<textarea id="editTextarea" name="secrettext" class="expand"><?php echo $secret['secrettext']; ?></textarea>
		<div class="word_count">Word Count: <span id="word_count"><?= $secret['word_count'] ?></span></div>

		<input type="hidden" value="<?php echo $id; ?>" name="id" />
		<input type="hidden" value="Save" name="save"/>
		<input type="hidden" name="word_count" id="word_count_input" value="<?= $secret['word_count'] ?>">
		<input type="submit" value="Save" class="blue add-submit"/>
		</form>
<a href="/<?=$secret['id'] ?>" class="grey cancel">Cancel</a>
		<a class="delete delete-btn" href="?delete=<?php echo $secret['id'];?>">Delete</a>

	<?php endforeach; ?>
</div></div></div>
<?php require 'footer.php'; ?>
