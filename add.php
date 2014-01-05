<?php
require 'initialize.php';
require 'top-main.php';
?>

<div class="add">

	<form action="/" id="form" method="POST" enctype="multipart/form-data">
	<input type="text" name="title" autocomplete="off" placeholder="title" class="input_title"/>



	<textarea placeholder="thoughts" name="secrettext" id="textarea" class="expand"></textarea>
	<div class="word_count">Word Count: <span id="word_count">0</span></div>


	
		
		<input type="hidden" name="add"/>
		<input type="hidden" name="word_count" id="word_count_input" value="">
		<input type="hidden" value="Add" class="add-submit"/>

	<input type="submit" value="Save" class="blue add-submit"/>
<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="cancel">Cancel</a>
	</form>

</div></div>

<?php require 'footer.php' ?>

