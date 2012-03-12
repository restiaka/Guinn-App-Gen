<?php
class Search_db
{
  protected $CI;
  protected $db;
    function search_db()
    {
		$this->CI = & get_instance();
		$this->CI->load->library('ezsql_mysql');
		$this->db = $this->CI->ezsql_mysql;
		$this->found = array();
		$this->select_fields = "*";
		$this->clauses ="";
    }
    function set_table($table)
    {
        # set table
        $this->table = $table;
    }
    function set_keyword($keyword)
    {
        # set keywords
        $this->keyword = explode(" ", $keyword);
    }
    function set_primarykey($key)
    {
        # set primary key
        $this->key = $key;
    }
    function set_fields($field)
    {
        # set fieldnames to search
        $this->field =$field;
    }
	function set_select_fields($field)
	{
	  $this->select_fields = $field;
	}
	function set_clause($clauses)
	{
	  $this->clauses = $clauses;
	}
    function set_dump()
    {
        # var dump objects
        echo '<pre>';
        var_dump($this->found);
        echo '</pre>';
    }
    function set_total()
    {
        # total results found
        return sizeof($this->found);
    }
    function set_result()
    {
        # find occurence of inputted keywords
        $key =  $this->key;
        for ($n=0; $n<sizeof($this->field); $n++)
        {
            for($i =0; $i<sizeof($this->keyword); $i++)
            {
                $pattern = trim($this->keyword[$i]);
                $sql = "SELECT ".$this->select_fields." FROM ".$this->table." WHERE ".$this->field[$n]." LIKE '%".$pattern."%'";
                if($this->clauses){
					$sql .= " ".$this->clauses;
				}
				
				if($result = $this->db->get_results($sql)){		
					foreach ($result as $row)
					{
						$this->found[] = $row->$key;
					}
				}
            }
        }

        $this->found = array_unique($this->found);
        return $this->found;
    }
} 