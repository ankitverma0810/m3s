<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Orders extends DatabaseObject
	{
		protected static $table_name="mh_orders";
		protected static $db_fields= array('id', 'order_id', 'ad_unique_id', 'tablename', 'user_id', 'ad_type', 
										   'ad_price', 'ad_start_date', 'ad_expiry_date', 'mode_of_payment',
										   'order_status', 'alert_status', 'order_added_date', 'order_modified_date');
		
		public $id;
		public $order_id;
		public $ad_unique_id;
		public $tablename;
		public $user_id;
		public $ad_type;
		public $ad_price;
		public $ad_start_date;
		public $ad_expiry_date;
		public $mode_of_payment;
		public $order_status;
		public $alert_status;
		public $order_added_date;
		public $order_modified_date;
		
		public $checkbox = array();
		
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
		
		public static function find_by_adid($id)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE ad_unique_id = '".$id."' LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function find_by_order_id($id)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE order_id = '".$id."' LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function check_order($id)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE ad_unique_id = '".$id."' LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function check_renew_order($id)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE ad_unique_id = '".$id."' LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function count_all_featured()
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM " . self::$table_name . " WHERE ad_type = 'featured'";
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}
		
		public static function count_all_spotlight()
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM " . self::$table_name . " WHERE ad_type = 'spotlight'";
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}
		
		public static function find_all()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name);
		}
		
		public static function find_all_active()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE order_status IN(2,4)");
		}
		
		public static function find_by_userid($id)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE user_id = {$id} AND order_status IN(2,4) ORDER BY order_added_date DESC");
		}
		
		public static function find_all_featured()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE ad_type = 'featured' AND order_status IN(2,4) ORDER BY RAND() LIMIT 5");
		}
		
		public static function find_spotlight()
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE ad_type = 'spotlight' AND order_status = 2 ORDER BY RAND()");
			return !empty($result) ? array_shift($result) : false;
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
		
		public function update_multiple($status, $type)
		{	
			global $database;	
			foreach ($this->checkbox as $value)
			{
				$orderid = str_before($value, '%');
				$newvalue = str_after($value, '%');
				$tablename = str_before($newvalue, '%');
				$adid = str_after($newvalue, '%');
								
				$sql = "UPDATE ".self::$table_name." SET ";
				if($status == 1)
				{
					$sql .= " order_status = 1,";
				}
				if($status == 2)
				{
					$sql .= " order_status = 2,";
					$sql .= " ad_start_date = '".strftime('%Y-%m-%d %H:%M:%S', time())."',";
					$sql .= " ad_expiry_date = '".date("Y-m-d h:i:s", strtotime('now' . " +15 day"))."',";
				}
				if($status == 3)
				{
					$renew_order = Orders::find_by_id($orderid);
					$sql .= " order_status = 2,";
					$sql .= " ad_expiry_date = '".date("Y-m-d h:i:s", strtotime($renew_order->ad_expiry_date . " +15 day"))."',";
				}
				$sql .= " order_modified_date = '".strftime('%Y-%m-%d %H:%M:%S', time())."'";
				$sql .= " WHERE id = $orderid";
				if($result = $database->query($sql))
				{
					$classified = Classifieds::find_by_uniqueid($adid, $tablename);
					if($classified)
					{
						if($status == 2 || $status == 3)
						{
							$classified->$type = 1;
						}
						else
						{
							$classified->$type = 0;
						}
						$classified->update($tablename);
					}
				}
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