<?php

class MainModel extends Model
{
	private $connection;

	public function __construct()
	{
		$arrIni = parse_ini_file(Config::DBCONFIG);
		$this->connection = mysqli_connect($arrIni["SERVER"], $arrIni["USERNAME"], $arrIni["PASSWORD"]);
	}

	public function getServer()
	{
		$arrServer = array();
		$arrServer[] = "localhost";
		
		return array("data" => $arrServer);
	}

	public function getDatabases($server)
	{
		$arrDatabases = array();
		$arrDatabases[] = "azwick_de";
		
		return array("data" => $arrDatabases);
	}

	public function getTables($database)
	{
		$arrTables = array();
		
		$sql = "SHOW TABLES FROM $database;";
		$qry = mysqli_query($this->connection, $sql);
		if ($qry)
		{
			while ($row = mysqli_fetch_array($qry))
			{
				$arrTables[] = $row[0];
			}
		}
		
		return array("sql" => $sql, "data" => $arrTables);
	}

	public function getData($server, $database, $table, $offset)
	{
		$row_count = 20;
		if ($offset > 0)
		{
			$offset = $row_count * $offset;
		}
		
		$arrSql = array();
		$arrFields = $this->getFields($server, $database, $table);
		$arrSql[] = $arrFields["sql"];
		
		$arrData = array();
		$arrData[] = $arrFields["data"];
		
		$sql = "SELECT " . implode(',', $arrFields["data"]) . " FROM $database.$table";
		$sql .= " LIMIT $offset, 20;";
		$arrSql[] = $sql;
		$qry = mysqli_query($this->connection, $sql);
		if ($qry)
		{
			while ($row = mysqli_fetch_array($qry, MYSQLI_NUM))
			{
				$arrData[] = $row;
			}
		}
		
		return array("sql" => $arrSql, "data" => $arrData);
	}

	private function getFields($server, $database, $table)
	{
		$arrFields = array();
		
		$sql = "SHOW COLUMNS FROM $database.$table;";
		$qry = mysqli_query($this->connection, $sql);
		if ($qry)
		{
			while ($row = mysqli_fetch_array($qry))
			{
				$arrFields[] = $row[0];
			}
		}
		
		return array("sql" => $sql, "data" => $arrFields);
	}
}

?>