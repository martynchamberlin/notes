<?php
require 'initialize.php';
require 'top-main.php';
?>

<div class="add">

	<form action="/" id="form" method="POST" enctype="multipart/form-data">
	<div contenteditable="true" class="input_title" autocomplete="off" type="text" name="title" placeholder="title"></div>
	<input type="hidden" name="title" class="input_title"/>



	<textarea placeholder="thoughts" name="text" id="textarea" class="expand"></textarea>
	<div class="categories">
	<label>Category:</label><input type="text" placeholder="(optional)" name="category">
	</div>
	<div class="word_count">Word Count: <span id="word_count">0</span></div>

	<div class="clear"></div>
	
		<div class="buttons">
		<input type="hidden" name="add"/>
		<input type="hidden" name="word_count" id="word_count_input" value="">
		<input type="hidden" value="Add" class="add-submit"/>	
	<input type="submit" value="Save" class="blue add-submit"/>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="cancel">Cancel</a>
</div>
	</form>

</div></div>

<?php require 'footer.php' ?>

