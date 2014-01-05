$(document).ready(function() {

	document.title = $('h1').html();

	if ( $('.post').length > 0 )
	{
		$('.post').first().addClass('active');
	}

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
					}, 510 );
				}
			});
			return false;
		}
	});


	if ( $('#form').length == 1 )
	{

		key('⌘+s, ⌘+enter, ctrl+s, ctrl+enter', function()
		{
			jQuery('#form').submit();
			return false;
		});
	}
	
	if ( $('.search_form input').length == 1 )
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
			window.location = $('.active a.title').attr('href');
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
	// I'm bending over backwards to not use any hardcoded numbers. 
	// Not sure it's really worth it. This is going to be confusing when
	// I look at it after a night's sleep lol.

	padding2 = $("#content").outerHeight() - $("#content").height();
	borderTop = $("#wrap #top").height();
	borderBottom = $("#wrap .bottom").height();

	$('#content').css("min-height", $(window).height() - ($("#wrap").outerHeight() - $("#wrap").height() ) - padding - padding2 - borderTop - borderBottom + "px");
}
