<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Register extends DatabaseObject
	{
		protected static $table_name="mh_reg_users";
		protected static $db_fields= array('id', 'email', 'password', 'mobile', 'landline', 'added_date', 'modified_date', 
										   'user_status', 'confirm_code');
		
		public $id;
		public $email;
		public $password;
		public $mobile;
		public $landline;
		public $added_date;
		public $modified_date;
		public $user_status;
		public $confirm_code;
		
		public $checkbox = array();
		public $errors = array();
		
		public static function find_by_search($email, $status)
		{
			global $database;			
			$search_email = $database->escape_value($email);
						
			$sql = "SELECT * FROM " . self::$table_name;
			if($email && $status)
			{
				$sql .= " WHERE email LIKE '%".$search_email."%' AND user_status IN($status)";
			}
			elseif($email)
			{
				$sql .= " WHERE email LIKE '%".$search_email."%'";
			}
			elseif($status)
			{
				$sql .= " WHERE user_status IN($status)";
			}
			return self::find_by_sql($sql);
		}
		
		public function change_password($new_password, $confirm_password)
		{
			if(empty($new_password))
			{
				$this->errors[] = "Please Enter New Password";
				return false;
			}
			if(empty($confirm_password))
			{
				$this->errors[] = "Please Enter Confirm Password";
				return false;
			}
			if($new_password != $confirm_password)
			{
				$this->errors[] = "New password and Confirm Password does not match";
				return false;
			}
			else
			{
				$this->password = $confirm_password;
				return true;
			}
			
		}
		
		public function add_user($user_email, $user_password, $user_mobile, $user_landline, $user_status, $confirm_code)
		{
			$result = self::find_by_sql("SELECT * FROM ". self::$table_name ." WHERE email = '".$user_email."'");			
			if(!empty($result))
			{
				$this->errors[] = "Email id already exists!";
			    return false;
			}
			else
			{
				$this->email = $user_email;
				$this->password = $user_password;					
				$this->mobile = $user_mobile;
				$this->landline = $user_landline;
				$this->user_status = $user_status;
				$this->confirm_code = $confirm_code;
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
		
		public static function find_by_email($email)
		{
			global $database;			
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE email = '".$email."' LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function find_all()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name);
		}
		
		public static function find_all_active()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE user_status = 2");
		}
		
		public static function find_all_users()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE user_status IN(1, 2, 3, 4)");
		}
		
		public static function count_all()
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM " . self::$table_name;
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}	
			
		public static function authenticate($email="", $password="")
		{
			global $database;
			$email = $database->escape_value($email);
			$password = $database->escape_value($password);
			
			$query = "SELECT * FROM ". self::$table_name;
			$query .= " WHERE email = '{$email}' ";
			$query .= "AND password = '{$password}' ";
			$query .= "AND user_status = 2 ";
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
				$this->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
				$this->update();
				return true;
			}
			if(!isset($this->id))
			{
				$this->added_date = strftime('%Y-%m-%d %H:%M:%S', time());
				$this->create();
				return true;
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
		
		public function update_multiple($status)
		{	
			global $database;	
			foreach ($this->checkbox as $value)
			{
				$sql = "UPDATE ".self::$table_name." SET ";
				$sql .= " user_status = $status";
				$sql .= " WHERE id = $value";
				$result = $database->query($sql);
			}
			if (!$result || $database->affected_rows() < 1)
			{				
				$this->errors[] = "ERROR - Unable To update";
				return false;
			} 
			else 
			{
				return true;
			} 
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
		
		public function delete_multiple()
		{	
			global $database;	
			foreach ($this->checkbox as $value)
			{
				$sql = "DELETE FROM ".self::$table_name;
				$sql .= " WHERE id = $value";
				$result = $database->query($sql);
			}
			if (!$result || $database->affected_rows() < 1)
			{				
				$this->errors[] = "ERROR - Unable To Delete";
				return false;
			} 
			else 
			{
				return true;
			} 
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