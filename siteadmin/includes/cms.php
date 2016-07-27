<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Cms extends DatabaseObject
	{
		protected static $table_name="mh_cms";
		protected static $db_fields= array('id', 'title', 'description', 'status', 'url', 'added_date', 'modified_date');
		public $id;
		public $title;
		public $description;
		public $status;
		public $url;
		public $added_date;
		public $modified_date;
		
		public $errors = array();
		
		public function add_page($page_title, $page_discription, $page_status , $page_url)
		{
			if(empty($page_title) || empty($page_discription))
			{
				$this->errors[] = "Please fill all the mandatory fields";
				return false;
			}
			else
			{
				$this->title = $page_title;
				$this->description = $page_discription;
				$this->status = $page_status;
				$this->url = $page_url;
				if($this->id)
				{
					$this->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());					
				}
				else
				{
					$this->added_date = strftime('%Y-%m-%d %H:%M:%S', time());
				}
				return true;
			}
		}		
		
		public static function find_by_sql($query="")
		{
			global $database;
			$result = $database->query($query);
			$object_array = array();
			while($row = $database->fetch_array($result))
			{
				$object_array[] = self::instantiate($row);
			}
			return $object_array;
		}
		
		public static function find_by_id($id=0)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id = {$id} LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function find_all()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status IN('0', '1')");
		}
				
		public static function authenticate($username="", $password="")
		{
			global $database;
			$username = $database->escape_value($username);
			$password = $database->escape_value($password);
			
			$query = "SELECT * FROM users ";
			$query .= "WHERE username = '{$username}' ";
			$query .= "AND password = '{$password}' ";
			$query .= "LIMIT 1";
			$result = self::find_by_sql($query);
			return !empty($result) ? array_shift($result) : false;
		}
		
		public function save()
		{
			if(!empty($this->errors))
			{
				return false;
			}
			if(isset($this->id))
			{
				$this->update();
				return true;
			}
			if(!isset($this->id))
			{
				$this->create();
				return true;
			}
			else
			{
				$this->errors[] = "Page not added";
				return false;
			}
		}
		
		protected function attributes()
		{
			$attributes = array();
			foreach(self::$db_fields as $key)
			{
				if(property_exists($this, $key))
				{
					$attributes[$key] = $this->$key; 
				}
			}
			return $attributes;
		}
		
		protected function sanitized_attributes()
		{
			global $database;
			$clean_attributes = array();
			foreach($this->attributes() as $key => $value)
			{
				$clean_attributes[$key] = $database->escape_value($value);
			}
			return $clean_attributes;
		}
		
		public function create()
		{
			global $database;
			$attributes = $this->sanitized_attributes();
			$sql = "INSERT INTO ".self::$table_name." (";
			$sql .= join(", ", array_keys($attributes));
			$sql .= ") VALUES ('";
			$sql .= join("', '", array_values($attributes));
			$sql .= "')";
			if($database->query($sql))
			{
				$this->id = $database->insert_id();
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function update()
		{
			global $database;
			$attributes = $this->sanitized_attributes();
			$attribute_pairs = array();
			foreach($attributes as $key => $value)
			{
				$attribute_pairs[] = "{$key}='{$value}'";
			}
			$sql = "UPDATE ".self::$table_name." SET ";
			$sql .= join(", ", $attribute_pairs);
			$sql .= " WHERE id=". $database->escape_value($this->id);
			$database->query($sql);
			return ($database->affected_rows() == 1) ? true : false;
		}
		
		public function delete()
		{
			global $database;
			$sql = "DELETE FROM ".self::$table_name;
			$sql .= " WHERE id =". $database->escape_value($this->id);
			$sql .= " LIMIT 1";
			$database->query($sql);
			return ($database->affected_rows() == 1) ? true : false;			
		}
		
		private function has_attribute($attribute)
		{
			$object_vars = get_object_vars($this);
			return array_key_exists($attribute, $object_vars);
		}
			
		private static function instantiate($record)
		{
			$member = new self;
			foreach($record as $key => $value)
			{
				if($member->has_attribute($key))
				{
					$member->$key = $value;
				}
			}
			return $member;
		}
	}
?>