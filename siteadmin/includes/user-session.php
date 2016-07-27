<?php 
	class Usersession
	{
		private $userlogged_in = false;
		public $reguser_id;
		public $reguser_email;
				
		function __construct()
		{
			//session_start();
			$this->check_loggin();
		}
				
		public function is_userlogged_in()
		{
			return $this->userlogged_in;
		}
				
		public function login($user)
		{
			if($user)
			{
				$_SESSION['reguser_id'] = $user->id;
				$_SESSION['reguser_email'] = $user->email;
				$this->userlogged_in = true;
			}
		}
		
		public function logout()
		{
			unset($_SESSION['reguser_id']);
			unset($_SESSION['reguser_email']);
			unset($this->reguser_id);
			unset($this->reguser_email);
			$this->userlogged_in = false;
		}
				
		private function check_loggin()
		{
			if(isset($_SESSION['reguser_id']) && isset($_SESSION['reguser_email']))
			{
				$this->reguser_id = $_SESSION['reguser_id'];
				$this->reguser_email = $_SESSION['reguser_email'];
				$this->userlogged_in = true;
			}
			else
			{
				unset($this->reguser_id);
				unset($this->reguser_email);
				$this->userlogged_in = false;
			}
		}
	}	
	$usersession = new Usersession();
?>