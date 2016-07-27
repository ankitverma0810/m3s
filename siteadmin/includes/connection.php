<?php require(LIB_PATH.DS."config.php"); ?>
<?php 
	class MySQLDatabases
	{
		private $connection;
		public $last_query;
		private $magic_quotes_active;
		private $new_enough_php;		
		function __construct()
		{
			$this->open_connection();
			$this->magic_quotes_active = get_magic_quotes_gpc(); 
			$this->new_enough_php = function_exists("mysql_real_escape_string");
		}		
		public function open_connection()
		{	
			$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
			if(!$this->connection)
			{
				die("Database connection failed: " . mysql_error());
			}
			else
			{
				$db_select = mysql_select_db(DB_NAME, $this->connection);
				if(!$db_select)
				{
					die("Database selection failed: " . mysql_error());
				}
			}
		}		
		public function close_connection()
		{
			if(isset($this->connection))
			{
				mysql_close($this->connection);
				unset($this->connection);
			}
		}		
		private function confirm_query($result_set)
		{
			if(!$result_set)
			{
				$output = "Database query failed: " . mysql_error() . "<br />";
				$output .= "Last Sql Query: " . $this->last_query;
				die($output);
			}
		}
				
		public function query($query)
		{
			$this->last_query = $query;
			$result = mysql_query($query, $this->connection);
			$this->confirm_query($result);
			return $result;
		}
				
		public function fetch_array($fetch)
		{
			return mysql_fetch_array($fetch);
		}
				
		public function num_rows($rows)
		{
			return mysql_num_rows($rows);
		}
				
		public function insert_id()
		{
			//get the last id inserted over the current db connection
			return mysql_insert_id($this->connection);
		}
				
		public function affected_rows()
		{
			return mysql_affected_rows($this->connection);
		}
				
		//This is for if we add any special character like ', ", / in our menu_name textfiled den what to do......
		public function escape_value($value)
		{
			//$magic_quotes_active = get_magic_quotes_gpc(); 
			//means that it will ignore special characters:- ', ", /, NULL and store them in db
			//$new_enough_php = function_exists("mysql_real_escape_string");
			//means that it will ignore special characters:- ', ", /, NULL and store them in db
			
			//php >= 4.3.0
			if($this->new_enough_php)
			{
				if($this->magic_quotes_active)
				{
					//stripslashes means that it will not ignore special characters  and will not get store in db:- ', '', /, NULL
					$quotes = stripslashes($value);
				}
				$quotes = mysql_real_escape_string($value);//means that it will ignore special characters:- ', '', /, NULL
			}
			else //before php 4.3.0
			{
				if(!$this->magic_quotes_active)
				{
					//addslashes means that it will ignore special characters and will get store in db:- ', '', /, NULL 
					$quotes = addslashes($value);
				}
			}
			return $quotes;
		}
	}	
	$database = new MySQLDatabases();
	//$databases->close_connection();
?>