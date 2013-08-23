<?

class View
{

	public static $startAt;
	public static $perPage;

	function __get( $property )
	{
		return $this->$property;
	}

	static function showPage( $full )
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
		foreach ($posts->data as $post)
		{
			if ($i == 0)
			{
				$output .= '<input type="hidden" id="noteID" value="' . $post['id'] .'"/>';
			}
			$i++;
			
			$output .= '<div class="post">';
			$output .= '<div class="left-column">';
			$output .= '<a class="title" href="/' . $post['id'] . '">' . $post['title'] . '</a>';
			$output .= '<div class="actions">';
			$output .= '<a href="/edit?id=' . $post['id'] . '" method="post">Edit</a>';
			$output .= ' <a class="delete" href="?delete=' . $post['id'] . '"/>Remove</a>';
			$output .= '</div>';
			$output .= '</div><!-- end .left -->';
			$output .= '<div class="date">' . date('F j, Y\<\b\r\/\>g:i A', $post['updatedate']);
			$output .= '</div></div>';

		}
			
		return $output;
	}

	static function formatSingle( $post )
	{	
		$post = $post->data[0];
		$output = "";
		$output .= '<div class="content">';
		$output .= '<h1>' . $post['title'] . '</h1>';
		$output .= '<div class="wrap">' . markdown($post['secrettext']) . '</div>';
		$output .= '<div class="word_count">Word Count: ' . $post['word_count'] . '</div>';
		$output .= '<a href="/edit/?id=' . $post['id'] . '" class="blue edit">Edit</a>';
		$output .= '<a href="/" class="grey">Cancel</a>';
		$output .= '<a href="/?delete=' . $post['id'] .'" class="grey delete">Delete</a>';
		$output .= '<input type="hidden" id="noteID" value="' . $post['id'] . '"/></div>';
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
					$output .= $i;
				}
				$output .= '</div>';
			}
		}
		return $output;
	}
}

