$(document).ready(function() {

	if ( $('body').is('.edit') || $('body').is('.add') )
	{
		// If they paste from an external source, fix that immediately
		// so the formatting is consistent with the rest of the site
		$('.input_title').keyup(function()
		{
			$('div.input_title').html( strip( $('div.input_title').html() ) );
		});

		// by default, it's okay to leave the commpose window, because
		// you haven't updated anything yet when the page initially loads
		var okay_to_leave = true;
		$('input, textarea, .input_title').keyup(function()
		{
			okay_to_leave = false;
		});

		$('form').submit(function()
		{
			okay_to_leave = true;
		});

		$('.buttons .delete').click(function()
		{
			okay_to_leave = true;
		});

		// If they have edited anything, warn them about it
		window.onbeforeunload = function() 
		{
			if ( ! okay_to_leave )
			{
				var yes = window.confirm("Lose your unsaved work?!");
				if (! yes) {			
					return false;
				}
			}
		}
	}
	// customize default select dropdowns
	$('select').select2();

	// populate the actual input with whatever is in the dropdown value
	$('select[name="select-category"]').change(function()
	{
		if ( $(this).val() !== "" )
		{
			$('input[name="category"]').val( $(this).val() );
		}
	});

	// Focus on title when you're on a new deal
	$('.add .input_title, .edit textarea').focus();

	// nice function that removes all inline HTML formatting
	function strip(html)
	{
	   var tmp = document.createElement("DIV");
	   tmp.innerHTML = html;
	   return tmp.textContent||tmp.innerText;
	}
	
	// fade in the primary page view whenever the page loads
	$('#content > *').hide().fadeIn(200);

	// more fade effects
	$('a.click').click(function()
	{
		$('.home > div + div').fadeOut(400);
	});

	// when they submit a form, fade out the page.
	// also make sure that our .input_title has a proper value
	$('form').submit(function()
	{
		$('.home > div + div').fadeOut(400);
		$('input.input_title').val( strip( $('div.input_title').html() ) );
	});

	// Our DOM doesn't yet supply a valid title, so here's a cheap fix
	if ( $('h1').length > 0 ) {
		document.title = $('h1').html();
	}
	
	key.filter = function(event)
	{
		var tagName = (event.target || event.srcElement).tagName;
		//return jQuery('textarea').is(':focus');
		return true;
	}

	// Let us tab in a textarea
	$("textarea").tabby();

	// Get rid of that ugly last border on the sidebar
	$("#sidebar .link").last().addClass('last');

	// No margin-bottom for the last <p> tag on the single.php content
	$(".single p").last().addClass('last');

	// Make sure you really want to delete that note
	$(".delete").click(function() {
		var yes = window.confirm("Seriously?");
		if (! yes) {			
			return false;
		}
		else if ( $(this).is('.trash-can') )
		{
			var content = $('textarea').val();
			var url = $(this).attr('href');
			var post = $(this).closest('.post');
			var is_active = false;
			if ( $(post).is('.active') )
			{
				is_active = true;
			}
			$.ajax({
 				type: "GET",
				url: url,
				data: { },

				success: function(html){
					$(post).fadeOut( 500 );
					setTimeout(function()
					{
						$(post).remove();
						$('.post').first().removeClass('not_first').addClass('first');
						$('.post').last().addClass('last');
						if ( is_active ) 
						{
							$('.post').first().addClass('active');
						}
					}, 510 ); // slighly longer than timeout
				}
			});
			return false;
		}
	});

	// by default, the first post is highlighted. this sets the stage
	// for the hot key implementations
	if ( $('.post').length > 0 )
	{
		$('.post').first().addClass('active');
	}


	// handles all of the hot keys

	if ( $('#form').length == 1 )
	{

		key('⌘+s, ⌘+enter, ctrl+s, ctrl+enter', function()
		{
			jQuery('#form').submit();

			return false;
		});
	}
	
	if ( $('.search_form input').length == 1 
		&& $('.single').length == 0 && ! $('body').is('.edit') )
	{
		key('⌘+f, ctrl+f', function()
		{
			$('.search_form input').focus().select();
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
		if ( $('.active .actions a').length > 0 )
		{
			window.location = $('.active .actions a').first().attr('href');
		}
		else if ( $('a.edit').length > 0 )
		{
			window.location = $('a.edit').attr('href');
		}
	});

	key('ctrl+v, o', function()
	{
		if ( $('.active a').length > 0 && ! $('input').is(':focus') )
		{
			window.location = $('.active .title a').attr('href');
		}
	});

	$('.home .top').addClass('blue-border');
	key('j', function()
	{
		if ( ! $('input').is(':focus') )
		{
			var active = $('.active');
			$('.home .top').removeClass('blue-border');
			if ( $(active).next().is('.post') > 0 )
			{
				$('.post').removeClass('previous');
				$(active).removeClass('active').next().addClass('active').prev().addClass('previous');
				$('html,body').animate({scrollTop: document.body.scrollTop +  $('.post').outerHeight() }, { duration: 0, queue: false });
			}
		}
	});

	key('k', function()
	{
		if ( ! $('input').is(':focus') )
		{
			var active = $('.active');
			if ( $(active).prev().is('.post') > 0 )
			{
				$('.previous').removeClass('previous');
				$(active).removeClass('active').prev().addClass('active').prev().addClass('previous');
				$('html,body').animate({scrollTop: document.body.scrollTop -  $('.post').outerHeight() }, { duration: 0, queue: false });

				if ( ! $('.active').prev().is( '.post' ) )
				{
					$('.home .top').addClass('blue-border');
				}
			}
		}
	});


	// handles the wordcount. This is probably a lot more tedious
	// than it ought to be. 
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
});


