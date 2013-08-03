$(document).ready(function() {

	key.filter = function(event)
	{
		var tagName = (event.target || event.srcElement).tagName;
		//return jQuery('textarea').is(':focus');
		return true;
	}


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


	if ( $('#form').length == 1 )
	{

		key('⌘+s, ⌘+enter', function()
		{
			jQuery('#form').submit();
			return false;
		});
	}
	
	if ( $('.search_form input').length == 1 )
	{
		key('⌘+f', function()
		{
			$('.search_form input').focus();
			return false;
		});
	}
		 
	key('ctrl+n', function()
	{
			window.location = "/add";
	});

	key('ctrl+w', function()
	{
			window.location = "/";
	});

	key('ctrl+e', function()
	{
			window.location = "/edit?id=" + $('#noteID').val();
	});

	key('ctrl+v', function()
	{
			window.location = "/" + $('#noteID').val();
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

	$('textarea').keyup(function()
	{
		wordCount();
	});

	$('textarea').autosize(); 
	var h = $('#wrap').outerHeight();
	// Make the white background as tall as the window. This is just cool
	var padding = ($(".inner").outerHeight(true) - $(".inner").height());
	padding += ($("#wrap").outerHeight(true) - $("#wrap").height());

	resize(h, padding);

	$(window).resize(function()
	{
		resize(h, padding);
	});
});

function resize(h, padding)
{
	$('#wrap').css("min-height", $(window).height() - padding + "px");


	// Content is the main white area minus padding minus the shadows.
	// I'm bending over bvackwards to not use any hardcoded numbers. 
	// Not sure it's really worth it. This is going to be confusing when
	// I look at it after a night's sleep lol.

	padding2 = $("#content").outerHeight() - $("#content").height();
	borderTop = $("#wrap #top").height();
	borderBottom = $("#wrap .bottom").height();

	$('#content').css("min-height", $(window).height() - ($("#wrap").outerHeight() - $("#wrap").height() ) - padding - padding2 - borderTop - borderBottom + "px");
}
