<?php

class Query
{
	// Data members are supposed to be private! Thanks Russell Sowell. 
	public $data;

	/** 
	 * `Get` methods rarely need to do anything other than return data. This
	 * means that if we have the opportunity to automate this process, we
	 * should consider doing so. __set()? Not so much.
	 */
	function __get( $property )
	{
		return $this->$property;
	}

	function __construct( $args = "" )
	{
		$sql = 'SELECT ';
		$sql .= isset($args['columns']) ? $args['columns'] : '*';
		$sql .= ' FROM notes';
		$sql .= isset($args['where']) ? ' ' . $args['where'] : '';
		$sql .= isset($args['orderby']) ? ' ORDER BY ' . $args['orderby'] : '';
		$sql .= isset($args['order']) ? ' ' . $args['order'] : '';
		$sql .= isset($args['offset']) ? ' LIMIT ' . $args['offset'] : '';
		$sql .= isset($args['posts_per_page']) ? ', ' . $args['posts_per_page'] : '';
		$core = Core::getInstance();
		$data = $core->pdo->query($sql);
		$this->data = $data->fetchAll();
	}
}

?>
