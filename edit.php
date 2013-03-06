<?php

require'initialize.php';

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
		<div class="word_count">Word Count: <span id="word_count">0</span></div>

		<input type="hidden" value="<?php echo $id; ?>" name="id" />
		<!--
		<div class="checkbox"><input id="show" type="checkbox" value="true"<?php if ( $secret['show_on_home'] == "true" ) { ?>checked="checked"<?php } ?> name="show_on_home"/><label for="show">Show excerpt on home page</label><br/>
		<input id="html" type="checkbox" value="y"<?php if ( $secret['disable_html'] == "y" ) { ?>checked="checked"<?php } ?> name="disable_html"/><label for="html">Disable HTML</label><br/>
		<input id="disable_nl2br" type="checkbox" value="true"<?php if ( $secret['is_nl2br'] == "true" ) { ?>checked="checked"<?php } ?> name="is_nl2br"/><label for="disable_nl2br">Disable nl2br</label><br/></div>
-->
		<input type="hidden" value="Save" name="save"/>
		<input type="hidden" name="word_count" id="word_count_input" value="">
		<input type="submit" value="Save" class="add-submit"/>
		</form>
<a href="/<?=$secret['id'] ?>" class="cancel">Cancel</a>
		<div class="link delete"><a href="?delete=<?php echo $secret['id'];?>">Delete</a>

	<?php endforeach; ?>
</div></div></div>
<?php require 'footer.php'; ?>
