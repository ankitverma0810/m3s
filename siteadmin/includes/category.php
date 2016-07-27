<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Category extends DatabaseObject
	{
		protected static $table_name="mh_category";
		protected static $db_fields= array('id', 'title', 'filename', 'status', 'url', 'tablename', 'meta_keywords', 'meta_description', 'added_date', 'modified_date');
		public $id;
		public $title;
		public $filename;
		public $status;
		public $url;
		public $tablename;
		public $meta_keywords;
		public $meta_description;
		public $added_date;
		public $modified_date;
		
		private $temp_path;
		protected $upload_dir = "../category";
		
		public $errors = array();
		protected $upload_errors = array(
			UPLOAD_ERR_OK 				=> "No errors.", //0
			UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.", //1
			UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",//2
			UPLOAD_ERR_PARTIAL 		=> "Partial upload.",//3
			UPLOAD_ERR_NO_FILE 		=> "No file.",//4
			UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",//6
			UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",//7
			UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."//8
		);
		
		public function attach_file($file)
		{
			if(!$file || empty($file) || !is_array($file))
			{
				$this->errors[] = "No File was uploaded";
				return false;
			}
			elseif($file['error'] != 0)
			{
				$this->errors[] = $this->upload_errors[$file['error']];
				return false;
			}
			else
			{
				$this->temp_path = $file['tmp_name'];
				$this->filename = basename($file['name']);
				$this->filename = str_replace(" ","_",$this->filename);
				$this->filename = strtolower($this->filename);
				$this->filename = rand(1,100000).$this->filename;
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
		
		public static function find_all_categries()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name);
		}
		
		//all visible categories....anywhere in the site
		public static function find_all_visible()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status = 1 ORDER BY title");
		}
		
		//for sidebar
		public static function find_limited_visible()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status = 1 ORDER BY RAND() LIMIT 7");
		}
		
		//for home page
		public static function find_all_visible_limited()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status = 1 ORDER BY title LIMIT 12");
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
			if(isset($this->id))
			{
				if($this->temp_path)
				{
					$target_path = SITE_ROOT.DS. $this->upload_dir .DS. $this->filename;
					if(file_exists($target_path))
					{
						$this->errors[] = "The Image {$this->filename} already exists.";
						return false;
					}
					
					if(move_uploaded_file($this->temp_path, $target_path))
					{
						$this->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
						if($this->update())
						{
							unset($this->temp_path);
							return true;
						}
					}
					else
					{
						$this->errors[] = "The file update failes";
						return false;
					}
				}
				else
				{
					if(empty($this->title) || empty($this->meta_keywords) || empty($this->meta_description))
					{
						$this->errors[] = "Please fill all the mandatory fields";
						return false;
					}
					$this->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());			
					if($this->update())
					{
						return true;
					}
					else
					{
						$this->errors[] = "Category update failes";
						return false;
					}
				}
			}
			else
			{
				if(empty($this->title) || empty($this->meta_keywords) || empty($this->meta_description))
				{
					$this->errors[] = "Please fill all the mandatory fields";
					return false;
				}
				if(empty($this->filename) || empty($this->temp_path))
				{
					$this->errors[] = "The file location was not available.";
					return false;
				}								
				$target_path = SITE_ROOT.DS. $this->upload_dir .DS. $this->filename;
				if(file_exists($target_path))
				{
					$this->errors[] = "The Image {$this->filename} already exists.";
					return false;
				}				
				if(move_uploaded_file($this->temp_path, $target_path))
				{
					$this->added_date = strftime('%Y-%m-%d %H:%M:%S', time());
					if($this->create())
					{
						unset($this->temp_path);
						return true;
					}
				}
				else
				{
					$this->errors[] = "The file upload failes, possibly due to incorrect permissions on the upload folder.";
					return false;
				}
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
		
		public static function create_table($table_name)
		{
			global $database;
			$query = " CREATE TABLE `$table_name` (";		
			$query .= " `id` int(11) NOT NULL auto_increment,";
			$query .= " `unique_id` varchar(96) NOT NULL,";
			$query .= " `user_id` int(11) NOT NULL,";
			$query .= " `state_id` int(11) NOT NULL,";
			$query .= " `location_id` int(11) NOT NULL,";
			$query .= " `category_id` int(11) NOT NULL,";
			$query .= " `subcategory_id` int(11) NOT NULL,";
			$query .= " `ad_type` varchar(60) NOT NULL,";
			$query .= " `title` varchar(250) NOT NULL,";
			$query .= " `description` text NOT NULL,";
			$query .= " `email` varchar(60) NOT NULL,";
			$query .= " `mobile` varchar(15) NOT NULL,";
			$query .= " `filename` varchar(250) NOT NULL,";			
			$query .= " `company_name` varchar(250) NOT NULL,";
			$query .= " `website_url` varchar(250) NOT NULL,";
			$query .= " `keywords` text NOT NULL,";			
			$query .= " `url` varchar(250) NOT NULL,";
			$query .= " `added_date` datetime NOT NULL,";
			$query .= " `modified_date` datetime NOT NULL,";
			$query .= " `expiry_date` datetime NOT NULL,";
			$query .= " `featured_status` tinyint(1) NOT NULL COMMENT '0=notfeatured,1=featured',";
			$query .= " `spotlight_status` tinyint(1) NOT NULL COMMENT '0=notspotlight,1=spotlight',";
			$query .= " `ad_status` tinyint(1) NOT NULL COMMENT '1=under-review, 2=approved, 3=rejected, 4=expired, 5=deleted',";
			$query .= " `alert_status` tinyint(1) NOT NULL COMMENT '0=greater than 5 days,1=less than 5 days,2=Confirm Sent Alert,3= Ad expired',";
			$query .= " PRIMARY KEY  (`id`))";			
			if($database->query($query))
			{
				return true;
			}
			else
			{
				return false;
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