<?php
class table
{
	protected $id = null;
	protected $table = null;
	
	function __construct()
	{
		
	}
	
	function bind($data)
	{
		//print_r($data);
		foreach($data as $key=>$value)
		{
			$this->$key = $value;
			//echo $key."--".$value;
		}
	}
	
	function load($id)
	{
		$this->id = $id;
		$dbo = database::getInstance();
		$sql = $this->buildQuery('load');
		
		$dbo->doQuery($sql);
		$row = $dbo->loadObjectList();
		foreach($row as $key=>$value)
		{
			if($key == "id")
			{
				continue;
			}
			$this->$key = $value;
		}
	}
	
	function store()
	{
		$dbo = database::getInstance();
		$sql = $this->buildQuery('store');
		print_r($sql);
		$dbo->doQuery($sql);
	}
	
	protected function buildQuery($task)
	{
		$sql = "";
		if($task == 'store')
		{
			if($this->id == "")
			{
				$keys = "";
				$values = "";
				$classVars = get_class_vars(get_class($this));
				$sql .= "Insert into {$this->table}";
				foreach($classVars as $key=>$value)
				{
					if($key == "id" || $key == "table")
					{
						continue;
					}
					
					$keys .= "{$key},";
					$values .= "'{$this->$key}',";
				}
				$sql .="(".substr($keys, 0, -1).") Values(".substr($values, 0, -1).")";
				//Insert into table (id, name) Values (1, 'soba');
			}else{
				$classVars = get_class_vars(get_class($this));
				$sql .= "Update {$this->table} set ";
				foreach($classVars as $key=>$value)
				{
					if($key == "id" || $key == "table")
					{
						continue;
					}
					$sql .= "{$key} = '{$this->$key}'";
				}
				$sql = substr($sql, 0, -2)." where id = {$this->id}";
			}
		}
		elseif($task == 'load')
		{
			$sql = "select * from {$this->table} where id = '{$this->id}'";
		}
		return $sql;
	
	}
}