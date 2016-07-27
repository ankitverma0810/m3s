<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Response extends DatabaseObject
	{
		protected static $table_name="mh_responses";
		protected static $db_fields= array('id', 'reg_userid', 'classified_id', 'classified_title', 'email', 'mobile', 'message', 'added_date');
		
		public $id;
		public $reg_userid;
		public $classified_id;
		public $classified_title;
		public $email;
		public $mobile;
		public $message;
		public $added_date;
		
		public function add_response($reg_userid, $classified_id, $classified_title, $email, $mobile, $message)
		{
			global $database;
			$this->reg_userid = $database->escape_value($reg_userid);
			$this->classified_id = $database->escape_value($classified_id);
			$this->classified_title = $database->escape_value($classified_title);
			$this->email = $database->escape_value($email);
			$this->mobile = $database->escape_value($mobile);
			$this->message = $database->escape_value($message);
			$this->added_date = strftime('%Y-%m-%d %H:%M:%S', time());
			return true;
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
			return self::find_by_sql("SELECT * FROM " . self::$table_name);
		}
		
		public static function count_all($id)
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM " . self::$table_name . " WHERE reg_userid = {$id}";
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}
		
		public static function find_by_adid($id)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE classified_id = '".$id."'");
		}
		
		public static function find_by_userid($id)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE reg_userid = '".$id."' ORDER BY YEAR(added_date) DESC, MONTH(added_date) DESC, DAY(added_date) DESC, Hour(added_date) DESC, Minute(added_date) DESC, Second(added_date) DESC");
		}
		
		public function save()
		{
			return isset($this->id) ? $this->update() : $this->create();
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