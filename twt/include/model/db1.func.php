<?php
	class Database1
	{
 
		var $num_queries1 = 0;
		
		var $connection1;

		private static $isconencted1 = false;
		static function isConnected1()
		{
			return Database1::$isconencted1;
		}

 
		var $error1;
 
		
		function error1($errdef)
		{
			$this->error1=$errdef;
		}
		function conn1($arr)
		{
	 
			if(!$this->connect1($arr))
			{
				if(isset($this->error1))
					$this->error1('mysql.error','connection error');
				return false;
			}
			return true;
		}
		function connect1($arr)
		{
			if(!is_null($this->connection1))
				return false;
			//echo "TRY CONN ".$CONFIG['database']['host']." DB:".$CONFIG['database']['dbname']."<br>";
			$this->connection1 = @mysql_connect(
					$arr['database']['host'],
					$arr['database']['dbuser'],
					$arr['database']['dbpassword']
				);
			if(!$this->connection1)
				return false;
			$selectdb1=$this->selectDb1($arr['database']['dbname']);
			Database1::$isconencted1=$selectdb1?true:false;
			if(Database1::$isconencted1&&$arr['database']['charset'])
				$this->sql1('SET NAMES '.$arr['database']['charset']);

			return Database1::$isconencted1;
		}
		function selectDb1($dbname)
		{
			return @mysql_select_db($dbname);
		}
		function close1()
		{
			if($this->connection1!=NULL)
				@mysql_close($this->connection1);
			$this->connection1=NULL;
			Database1::$isconencted1 = false;
		}
		
 
		function sql1($query)
		{
			if(!$this->connection1)
				return false;
			//$this->conn();
			$timestamp=microtime(true);
			$result=mysql_query($query,$this->connection1);
			$timestamp=microtime(true)-$timestamp;
			$this->num_queries++;
			//if($timestamp>5)die($query);
 
			return $result;
		}
		
		static function rows1($result)
		{
			return $result==NULL?0:mysql_num_rows($result);
		}
		
		static function getRow1($result)
		{
			return $result?mysql_fetch_assoc($result):false;
		}

 
	}
?>