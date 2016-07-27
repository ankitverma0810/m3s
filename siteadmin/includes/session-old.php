<?php 
	class Session
	{
		private $logged_in = false;
		public $user_id;
		public $username;
		public $message;		
		function __construct()
		{
			session_start();
			$this->check_message();
			$this->check_loggin();
		}		
		public function message($msg="")
		{
			if(!empty($msg))
			{
				$_SESSION['message'] = $msg;
			}
			else
			{
				return $this->message;
			}
		}		
		public function check_message()
		{
			if(isset($_SESSION['message']))
			{
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			}
			else
			{
				$this->message = "";
			}
		}		
		public function is_logged_in()
		{
			return $this->logged_in;
		}		
		public function login($user)
		{
			if($user)
			{
				$_SESSION['user_id'] = $user->id;
				$_SESSION['username'] = $user->username;
				$this->logged_in = true;
			}
		}		
		public function logout()
		{
			unset($_SESSION['user_id']);
			unset($_SESSION['username']);
			unset($this->user_id);
			unset($this->username);
			$this->logged_in = false;
		}		
		private function check_loggin()
		{
			if(isset($_SESSION['user_id']) && isset($_SESSION['username']))
			{
				$this->user_id = $_SESSION['user_id'];
				$this->username = $_SESSION['username'];
				$this->logged_in = true;
			}
			else
			{
				unset($this->user_id);
				unset($this->username);
				$this->logged_in = false;
			}
		}
	}	
	$session = new Session();
	$message = $session->message();
?>