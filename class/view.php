<?

class View
{

	public static $startAt;
	public static $perPage;

	function __get( $property )
	{
		return $this->$property;
	}

	static function showPage( $full = true )
	{
		$url = $_SERVER["REQUEST_URI"];
		$url = explode('?', $url);
		$url = array_shift($url);
		$url = !$full ? basename($url) : $url;
		return $url;
	}

	static function formatArchive( $posts )
	{
		$output = "";
		$i = 0;
		if ( count( $posts->data ) == 0 )
		{
			echo 'Sorry, no results found.';
		}

		foreach ($posts->data as $post)
		{
			if ($i == 0)
			{
				$class = 'first';
			}
			else {
				$class = "not_first";
			}
			$i++;
			if ( count( $posts->data ) == $i )
			{
				$class .= " last";
			}
			if ( $post['name'] == "" )
			{	
				$class .= ' no_category';
			}
			
			$delta = "";
			if ( $post['updatedate'] > $post['publishdate'] )
			{
				$second = 1;
				$minute = $second * 60;
				$hour = $minute * 60;
				$day = $hour * 24;
				$week = $day * 7;
				$month = $day * 30;
				$year = $month * 12;

				$d_seconds = time() - $post['updatedate'];
				if ( $d_seconds > $year )
					$delta = floor( $d_seconds / $year ) . " year";

				else if ( $d_seconds > $month )
					$delta = floor( $d_seconds / $month ) . " month";

				else if ( $d_seconds > $week )
					$delta = floor( $d_seconds / $week ) . " week";

				else if ( $d_seconds > $day )
					$delta = floor( $d_seconds / $day ) . " day";

				else if ( $d_seconds > $hour )
					$delta = floor( $d_seconds / $hour ) . " hour";

				else if ( $d_seconds > $minute )
					$delta = floor( $d_seconds / $minute ) . " minute";

				else // if ( $d_seconds > $second )
					$delta = floor( $d_seconds / $second ) . " second";
	
				if ( substr( $delta, 0, 1 ) != '1' || substr( $delta, 1, 1 ) != ' ')
					$delta .= 's';
				$delta = ' &bull; Updated ' . $delta . ' ago.';
			}
			
			$output .= '<div class="post archive ' . $class . '">';
			$output .= '<div class="left-column">';
			$output .= '<div class="actions">';
			$output .= '<a class="edit" href="/edit?id=' . $post['nid'] . '" method="post">&#9998;</a>';
			$output .= ' <a class="delete trash-can" href="?delete=' . $post['nid'] . '"/>&#59177;</a>';
			$output .= '</div>';
			$output .= '<span class="title"><a href="' . View::url( $post['nid'], $post['title'] ) . '">' . $post['title'] . '</a><br/><span class="light-stuff">';
			if ( $post['name'] != "" )
			{
				$output .= 'Filed under <a href="/search/?q=in:' . $post['name'] . '">' . $post['name'] . '</a> <span class="bull">&bull;</span> ';
			}
			$output .= '<span class="word-count">' . number_format( $post['word_count'] ) . ' words</span><span class="update-date">' . $delta . '</span></span></span></div><!-- end .left -->';
			$output .= '<div class="date">' . date('F j, Y\<\b\r\/\>g:i A', $post['publishdate']);
			$output .= '</div><div class="clear"></div></div>';

		}
			
		return $output;
	}
	
	static function url( $id, $title  )
	{
		$output = $id . '-';
		
		$title = explode(' ', $title);
		foreach ($title as $word)
		{
			// strip anything that's not a letter or number
			$word = preg_replace("/[^a-zA-Z0-9\s]/", "", $word);
			if (strlen($word) > 0)
			{
				$output .= strtolower($word) . '-';
			}
		}
		$output = substr($output, 0, -1);
		$output .= '/';
		return $output;
	}

	static function formatSingle( $post )
	{	
		//$post['text'] = preg_replace('@(\s|^)(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-1234567890=]*(\?\S+)?[^\.\s])?)?)@', '$1<notextile><a href="$2" target="_blank">$2</a></notextile>', $post['text']);
		$output = "";
		$output .= '<div class="content">';
		$output .= '<h1>' . $post['title'] . '</h1>';
		$output .= '<div class="wrap">' . markdown($post['text']) . '</div>';
		$output .= '<div class="categories">
	<label>Category:</label><input type="text" disabled value="' .  $post['cat_name'] . '" placeholder="(not specified)" name="category">
	</div><div class="word_count">Word Count: ' . $post['word_count'] . '</div>';
		$output .= '<div class="clear"></div><div class="buttons">
		<a href="/edit/?id=' . $post['nid'] . '" class="blue edit">Edit</a>';
		if ( $post['archived'] == "0" )
		{
			$output .= '<a href="/archive/?aid=' . $post['nid'] . '" class="blue archive">Archive</a>';
		}
		else
		{
			$output .= '<a href="/archive/?naid=' . $post['nid'] . '" class="blue archive">Restore</a>';
		}

		$output .= '<a href="/" class="grey">Cancel</a>';
		$output .= '<a href="/?delete=' . $post['nid'] .'" class="grey delete">Delete</a>';
		$output .= '<input type="hidden" id="noteID" value="' . $post['nid'] . '"/></div></div>';
		return $output;

	}

	static function paginate( $total )
	{
	
		$output = "";
		// Define how many results we want per page
		self::$perPage = 10;
		$total_pages = ceil($total / self::$perPage);

		// Find out what page we're on
		if (isset($_GET['p']))
		{
			$current_page = $_GET['p'];
			if ( $current_page < 1 )
			{
				$current_page = 1;
			}
			if ( $current_page > $total_pages ) 
			{
				$current_page = $total_pages;
			}
		}
		else 
		{
			$current_page = 1;
		}

		self::$startAt = $current_page * self::$perPage - self::$perPage;

		if ($total_pages > 10 && $current_page >= 7) 
		{
			$inFront = $total_pages - $current_page;
			//echo $inFront;
			if ($inFront < 4)
			{
				$start = $current_page - (10 - (1 + $inFront));
				$finish = $inFront + $current_page;
			}

			else
			{
				$start = $current_page - 5;
				$finish = $current_page + 4;
			}
		}
		else 
		{
			$start = 0;
			$finish = 10;
		}

		if ($start < 1 || $finish < 11)
		{
			$start = 1;
		}

		if ($finish > $total_pages)
		{
			$finish = $total_pages;
		}

		global $url; echo $url;

		if ($finish > 1) 
		{
			for ($i = $start; $i <= $finish; $i++)
			{ 
		 		$output .= '<div class="item">';
				$string = "";
				$count = 0;

				foreach ($_GET as $key => $value)
				{
					// don't put this in there twice
					if ($key != 'p')
					{
						if ($count > 0)
						{
							$string .= '&';
						}
						$string .= $key;
						if (!empty($value))
						{
							$string .= '=' . urlencode($value);
						}
						$count++;
					}
				}

				if ($i != $current_page)
				{
					$output .= '<a href="'.Config::$home . View::showPage( true );
					if (empty($string) && $i != 1) 
					{ 
							$output .= '?p=' . $i;
					}
					else if (!empty($string))
					{
						$output .= '?' . $string;
						if ($i != 1)
						{
							$output .= '&p=' . $i;
						}
					}
					$output .= '">';
					$output .= $i . '</a>';
				}

				else 
				{
					$output .= '<span class="no-link">' . $i . '</span>';
				}
				$output .= '</div>';
			}
		}
		return $output;
	}
}

