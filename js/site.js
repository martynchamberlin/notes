$(document).ready(function() {

	// Let us tab in a textarea
	$("textarea").tabby();

	// Focus on title when you're on a new deal
	$('.add .input_title, .edit textarea').focus();

	// Get rid of that ugly last border on the sidebar
	$("#sidebar .link").last().addClass('last');

	// No margin-bottom for the last <p> tag on the single.php content
	$(".single p").last().addClass('last');

	// Make sure you really want to delete that
	$(".delete").click(function() {
		var yes = window.confirm("Seriously?");
		if (! yes) {
			return false;
		}
	});

	$(document).bind('keydown', 'ctrl+s', function()
	{ 
		$('#form').submit();
	});

	$(document).bind('keydown', 'ctrl+n', function()
	{ 
		window.location = "/add";
	});

	$(document).bind('keydown', 'ctrl+w', function()
	{ 
		window.location = "/";
	});

	var postID = $('#noteID').val();

	$(document).bind('keydown', 'ctrl+e', function(){ 
		window.location = "/edit?id=" + postID; 
	});

	$(document).bind('keydown', 'ctrl+v', function()
	{ 
		window.location = "/" + postID; 
	});

	var searchForm = $('.search_form input');

	$(document).bind('keydown', 'ctrl+f', function(){ 
		searchForm.focus(); 
	});
});

$(document).ready(function() {

	var wordCount = function()
	{
	
		if ($('textarea').length > 0)
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
		$('#word_count_input').val(count);
	}
	
	};

	
	wordCount();
	$('textarea').keyup(function()
	{
		wordCount();
	});

	$('textarea').autosize(); 

	// Make the white background as tall as the window. This is just cool
	var h = $('#wrap').outerHeight();
	var padding = parseInt(jQuery(".inner").css("padding-top"));
	padding += parseInt(jQuery(".inner").css("padding-bottom"));
	padding += parseInt(jQuery(".inner").css("margin-top"));
	padding += parseInt(jQuery(".inner").css("margin-bottom"));
	padding += parseInt(jQuery("#wrap").css("padding-top"));
	padding += parseInt(jQuery("#wrap").css("padding-bottom"));
	padding += parseInt(jQuery("#wrap").css("margin-top"));
	padding += parseInt(jQuery("#wrap").css("margin-bottom"));

	if (h < $(window).height())
	{
		$('.inner').css("min-height", $(window).height() - padding + "px");
	}

	$('#content').css("min-height", $('.inner').innerHeight() - 87);
});

