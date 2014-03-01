<?php

require'initialize.php';

require 'top-main.php';

?><div class="edit"><?php

	$db = Core::getInstance();
	$db = $db->pdo;

	$id = $_GET['id'];
	$query = "SELECT *, N.id as nid, C.name as cat_name FROM notes N LEFT JOIN categories_lookup L on N.id = L.nid LEFT JOIN categories C on L.cid = C.id WHERE N.id=$id";
	$result = $db->query($query);

	foreach ($result as $notes): ?>
		<form id="form" action="/?savenotes" method="post">
			<div contenteditable="true" class="input_title" autocomplete="off" type="text" name="title" placeholder="title"><? echo $notes['title'] ?></div>
			<input type="hidden" name="title" class="input_title"/>
			<input type="hidden" name="url" placeholder="permalink" value="<?php echo $notes['url'] ?>"/>
		<textarea id="editTextarea" placeholder="thoughts" name="text" class="expand"><?php echo $notes['text']; ?></textarea>
		<div class="categories">
	<label>Category:</label><input type="text" value="<?php echo $notes['cat_name']; ?>" placeholder="(optional)" name="category">
	</div>

		<div class="word_count">Word Count: <span id="word_count"><?= $notes['word_count'] ?></span></div>
	<div class="clear"></div>

		<div class="buttons">
		<input type="hidden" value="<?php echo $id; ?>" name="id" />
		<input type="hidden" value="Save" name="save"/>
		<input type="hidden" name="word_count" id="word_count_input" value="<?= $notes['word_count'] ?>">
		<input type="submit" value="Save" class="blue add-submit"/>
		</form>
		
<a href="/<?=$notes['nid'] ?>" class="grey cancel">Cancel</a>
		<a class="delete delete-btn" href="?delete=<?php echo $notes['nid'];?>">Delete</a>
		</div>
	<?php endforeach; ?>
</div></div></div>
<?php require 'footer.php'; ?>
