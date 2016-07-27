<?php require_once(LIB_PATH.DS."connection.php"); ?>
<?php 
	class Classifieds extends DatabaseObject
	{
		protected static $table_name;
		protected static $db_fields= array('id', 'unique_id', 'user_id', 'state_id', 'location_id', 'category_id', 
		'subcategory_id', 'ad_type', 'title', 'description', 'email', 'mobile', 'filename', 'company_name', 'website_url', 'keywords', 'url',
		'added_date', 'modified_date', 'expiry_date', 'featured_status', 'spotlight_status', 'ad_status', 'alert_status');
		
		public $id;
		public $unique_id;
		public $user_id;
		public $state_id;
		public $location_id;
		public $category_id;
		public $subcategory_id;
		public $ad_type;
		public $title;
		public $description;
		public $email;
		public $mobile;
		public $filename;		
		public $company_name;
		public $website_url;
		public $keywords;		
		public $url;
		public $added_date;
		public $modified_date;
		public $expiry_date;
		public $featured_status;
		public $spotlight_status;
		public $ad_status;
		public $alert_status;
		
		private $temp_path;
		protected $upload_dir = "../classified-ads";
		
		public $checkbox = array();
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
		
		public function find_by_search($userid, $code, $status, $state, $categoryid, $type)
		{
			global $database;
			$categories = Category::find_all_categries();
			$count = count($categories);
			$num = 0;
			$sql = "";
			foreach($categories as $category)
			{
				$num++;
				$sql .= " SELECT * FROM ".$category->tablename." WHERE user_id = {$userid}";
				if($code)
				{
					$sql .= " AND unique_id = '".$code."'";
				}
				if($status)
				{					
					$sql .= " AND ad_status = {$status}";
				}
				if($state)
				{
					$sql .= " AND state_id = {$state}";
				}
				if($categoryid)
				{
					$sql .= " AND category_id = {$categoryid}";
				}
				if($type)
				{
					$sql .= " AND ad_type = {$type}";
				}
				$sql .= " AND featured_status = 0 AND spotlight_status = 0";
				if($num != $count)
				{
					$sql .= " UNION ALL";
				}
			}
			return self::find_by_sql($sql);
		}
		
		public function attach_file($file)
		{
			if($file['error'] == 4)
			{
				$this->filename = "";
				$this->temp_path = "";
			}
			else
			{
				if($file['error'] != 0)
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
		
		public static function find_by_adid($tablename, $adid)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . $tablename . " WHERE id = {$adid} LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function find_by_uniqueid($uniqueid, $tablename)
		{
			global $database;
			$result = self::find_by_sql("SELECT * FROM " . $tablename . " WHERE unique_id = '".$uniqueid."' AND ad_status IN('1','2','3','4','5') LIMIT 1");
			return !empty($result) ? array_shift($result) : false;
		}
		
		public static function find_all()
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status IN('1', '2', '3', '4', '5')");
		}
		
		public static function find_by_subcategory($tablename, $subcategory)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . $tablename . " WHERE subcategory_id = {$subcategory} AND ad_status = 2 ORDER BY 
									  YEAR(expiry_date) DESC, MONTH(expiry_date) DESC, DAY(expiry_date) DESC, Hour(expiry_date) DESC, 
									  Minute(expiry_date) DESC, Second(expiry_date) DESC LIMIT 4");
		}
		
		public static function find_latest_ads($tablename, $subcategory, $state)
		{
			global $database;
			$sql = "SELECT * FROM " . $tablename;
			$sql .= " WHERE subcategory_id = {$subcategory}";
			$sql .= " AND state_id = {$state}";
			$sql .= " AND ad_status = 2";
			$sql .= " ORDER BY added_date DESC";
			$sql .= " LIMIT 5";
			return self::find_by_sql($sql);
		}
		
		public static function latest_classifieds_all($tablename, $subcategory, $state)
		{
			global $database;
			$sql = "SELECT * FROM " . $tablename;
			$sql .= " WHERE subcategory_id = {$subcategory}";
			$sql .= " AND state_id = {$state}";
			$sql .= " AND ad_status = 2";
			return self::find_by_sql($sql);
		}
		
		public static function count_all($tablename, $ad_type)
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM ".$tablename;
			$sql .= " WHERE ad_type = ".$ad_type;
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}
		
		//counting and displaying ads in the listing page
		public static function classified_by_ad_type($category, $subcategory, $state, $name, $ad_type, $page="", $per_page="", $total_count="")
		{
			global $database;
			$pagination = new pagination($page, $per_page, $total_count);
			
			$main_categories = Category::find_all_visible();
			$count = count($main_categories);
			$num = 0;
			$sql = "";
			foreach($main_categories as $main_category)
			{
				$num++;
				$sql .= " SELECT * FROM ".$main_category->tablename;
				$sql .= " WHERE ad_status = 2";
				$sql .= " AND ad_type = '".$ad_type."'";
				if($category != "")
				{
					$sql .= " AND category_id = {$category}";
				}
				if($subcategory != "")
				{
					$sql .= " AND subcategory_id = {$subcategory}";
				}
				if($state != "")
				{
					$sql .= " AND state_id = {$state}";
				}
				if($name != "")
				{
					$sql .= " AND title LIKE '%".$name."%'";
				}
				if($num != $count)
				{
					$sql .= " UNION ALL";
				}
			}
			$sql .= " ORDER BY YEAR(modified_date) DESC, MONTH(modified_date) DESC, DAY(modified_date) DESC, Hour(modified_date) DESC, 
					  Minute(modified_date) DESC, Second(modified_date) DESC, YEAR(added_date) DESC, MONTH(added_date) DESC, DAY(added_date) DESC,
					  Hour(added_date) DESC, Minute(added_date) DESC, Second(added_date) DESC";
			if($page != "" && $per_page != "" && $total_count != "")
			{
				$sql .= " LIMIT {$per_page}";
				$sql .= " OFFSET {$pagination->offset()}";
			}
			return self::find_by_sql($sql);
		}
		
		public static function count_all_subcategories($tablename, $subcategory_id, $ad_type)
		{
			global $database;
			$sql = "SELECT COUNT(*) FROM ".$tablename;
			$sql .= " WHERE ad_type = ".$ad_type;
			$sql .= " AND subcategory_id = ".$subcategory_id;
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}
		
		public static function find_by_tablename($tablename)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . $tablename . " WHERE ad_status = 2");
		}
		
		public static function find_by_user($tablename, $user_id)
		{
			global $database;
			return self::find_by_sql("SELECT * FROM " . $tablename . " WHERE user_id = $user_id AND featured_status = 0 AND spotlight_status = 0");
		}
		
		public function save($tablename)
		{
			if(isset($this->id))
			{
				if(empty($this->filename) || empty($this->temp_path))
				{
					if($this->update($tablename))
					{
						return true;
					}
					else
					{
						$this->errors[] = "Ad Created fails!";
						return false;
					}
				}
				else
				{							
					$target_path = SITE_ROOT.DS. $this->upload_dir .DS. $this->filename;
					if(file_exists($target_path))
					{
						$this->errors[] = "The Image {$this->filename} already exists.";
						return false;
					}				
					if(move_uploaded_file($this->temp_path, $target_path))
					{
						if($this->update($tablename))
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
			else
			{
				if(empty($this->filename) || empty($this->temp_path))
				{
					if($this->create($tablename))
					{
						return true;
					}
					else
					{
						$this->errors[] = "Ad Created fails!";
						return false;
					}
				}
				else
				{							
					$target_path = SITE_ROOT.DS. $this->upload_dir .DS. $this->filename;
					if(file_exists($target_path))
					{
						$this->errors[] = "The Image {$this->filename} already exists.";
						return false;
					}				
					if(move_uploaded_file($this->temp_path, $target_path))
					{
						if($this->create($tablename))
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
		
		public function create($tablename)
		{
			global $database;
			$attributes = $this->sanitized_attributes();
			$sql = "INSERT INTO ".$tablename." (";
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
		
		public function update($tablename)
		{
			global $database;
			$this->modified_date = strftime('%Y-%m-%d %H:%M:%S', time());
			$attributes = $this->sanitized_attributes();
			$attribute_pairs = array();
			foreach($attributes as $key => $value)
			{
				$attribute_pairs[] = "{$key}='{$value}'";
			}
			$sql = "UPDATE ".$tablename." SET ";
			$sql .= join(", ", $attribute_pairs);
			$sql .= " WHERE id=". $database->escape_value($this->id);
			$database->query($sql);
			return ($database->affected_rows() == 1) ? true : false;
		}
		
		public function update_multiple($status)
		{	
			global $database;						
			include_once('../mail_files/htmlMimeMail.php');
			
			foreach($this->checkbox as $value)
			{
				$tablename = str_after($value, '%');
				$adid = str_before($value, '%');								
				$sql = "UPDATE ".$tablename." SET ";
				$sql .= " ad_status = $status,";
				$sql .= " modified_date = '".strftime('%Y-%m-%d %H:%M:%S', time())."'";
				$sql .= " WHERE id = ".$adid;
				$result = $database->query($sql);
				if($result)
				{
					if($status == 2)
					{
						$classified = Classifieds::find_by_adid($tablename, $adid);						
						$alert = new Alerts();
						$alert->add_alert($classified->user_id, $classified->email, $classified->category_id, $classified->subcategory_id, 
										  $classified->state_id, $classified->location_id);
						if($alert->save())
						{
							//----------------------reading the email template starts from here-----------------------------//
							$file = SITE_ROOT_URL."email-templates/ad-approved.html";
							$content = "";
							if($handle = fopen($file, 'r'))
							{
								while(!feof($handle)) //feof means until file end of read everything
								{
									$content .= fgets($handle); // get everything from this file and add into $content.
								}
								fclose($handle);
							}
							
							$premium_link = SITE_ROOT_URL.'featured-spotlight-ads.php?adid='.$classified->unique_id.'&tablename='.$tablename;
							$replaceFrom = array("{RECIPENT_NAME}","{UNIQUE_ID}","{PREMIUM_LINK}");
							$replaceTo = array($classified->email,$classified->unique_id,$premium_link);
							$emailContent = str_replace($replaceFrom,$replaceTo,$content);
							//----------------------reading the email template ends from here-----------------------------//
					
							$mail = new htmlMimeMail();	
							$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);														
							$emailTo=$classified->email;						
							$mail->setFrom(EMAIL_FROM);  
							$mail->setSubject("{$classified->title} - Approved");		 
							$mail->setHtml($emailContent);
							$mail->send(array($emailTo),'smtp');
						}
					}
					if($status == 3)
					{
						$classified = Classifieds::find_by_adid($tablename, $adid);	
						$mail = new htmlMimeMail();	
						$mail->setSMTPParams(SMTP_HOST,SMTP_PORT,SMTP_HELO,SMTP_AUTH,SMTP_USER,SMTP_PASS);		
						$msg_to_include = 'Your ad posted on M3H.com has been Rejected.';						
						$emailTo=$classified->email;						
						$mail->setFrom(EMAIL_FROM);  
						$mail->setSubject("{$classified->title} - Rejected");		 
						$mail->setHtml($msg_to_include);
						$mail->send(array($emailTo),'smtp');
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
		
		public function delete($tablename, $delete_id)
		{
			global $database;
			$sql = "DELETE FROM ".$tablename;
			$sql .= " WHERE id =". $delete_id;
			$sql .= " LIMIT 1";
			$database->query($sql);
			return ($database->affected_rows() == 1) ? true : false;			
		}
		
		public function delete_multiple()
		{	
			global $database;	
			foreach ($this->checkbox as $value)
			{
				$sql = "DELETE FROM ".str_after($value, '%');
				$sql .= " WHERE id =".str_before($value, '%');
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