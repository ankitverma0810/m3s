<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Subcategory extends DatabaseObject
	{
		protected static $table_name="mh_subcategory";
		protected static $db_fields= array('id', 'category_id', 'title', 'status', 'url', 'meta_keywords', 'meta_description', 'added_date', 'modified_date');
		public $id;
		public $category_id;
		public $title;
		public $status;
		public $url;
		public $meta_keywords;
		public $meta_description;
		public $added_date;
		public $modified_date;
		
		public $errors = array();
		
		public function add_subcategory($category_id, $subcategory_title, $subcategory_status, $subcategory_url, $meta_keywords, $meta_description)
		{
			if(empty($category_id) || empty($subcategory_title) || empty($meta_keywords) || empty($meta_description))
			{
				$this->errors[] = "Please fill all the mandatory fields";
				return false;
			}
			else
			{
				$this->category_id = $category_id;
				$this->title = $subcategory_title;
				$this->status = $subcategory_status;
				$this->url = $subcategory_url;
				$this->meta_keywords = $meta_keywords;
				$this->meta_description = $meta_description;
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
		
		public static function find_by_url($url)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE url = '".$url."' LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function find_all()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status IN('0', '1')");
		}
		
		public static function count_all()
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM " . self::$table_name;
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}
		
		public static function find_by_categoryid($id)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE category_id = {$id} AND status = 1");
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
				$this->errors[] = "Subcategory not added";
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
			$this->added_date = strftime('%Y-%m-%d %H:%M:%S', time());
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
			$this->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
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