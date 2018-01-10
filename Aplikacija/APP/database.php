<?php
class database
{
	private $host;
	private $user;
	private $pass;
	private $dbName;
	private static $instance;
	private $connection;
	private $results;
	private $numRows;

	private function __construct()
	{

	}

	static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	function connect($host, $user, $pass, $dbName)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->dbName = $dbName;

		$this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->dbName);
		return $this->connection;
	}

public function doQuery($sql)
	{
		$this->results = mysqli_query($this->connection, $sql);
		
		if(is_bool($this->results))
	    {
	$this->numRows = mysqli_affected_rows($this->connection);
        }
		else
		{
	$this->numRows = mysqli_num_rows($this->results);
        }
	}

	public function loadObjectList()
	{
		$obj = "No Results!";
		if($this->results)
		{
			$obj = $this->results;

		}
		return $obj;
	}
	public function getNumRows(){
		return $this->numRows;
	}
}