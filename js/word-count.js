$(document).ready(function() {

	// Let us tab in a textarea
	$("textarea").tabby();

	var wordCount = function()
	{
	
		var text = $('textarea').val();
		var count = 0;
		var increment = true;

		for (var i = 0; i < text.length; i++)
		{
			if (isSolid(text[i]))
			{
				if (increment)
				{
					count++;
					increment = false;
				}
			}
			else
				increment = true;
		}

		function isSolid(letter)
		{
			if (letter == ' ' || letter == '\n' || letter == '\t' || letter == String.fromCharCode(8212))
				return false;
			return true;
		}

		$('#word_count').html('').append(count);
	
	};

	
	wordCount();
	$('textarea').keyup(function()
	{
		wordCount();
	});
});
