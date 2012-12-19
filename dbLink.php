<?php
	class dbLink {
		private $_link;
		
		public function __construct()
		{
			$host = 'localhost';
			$user = 'root';
			$pass = '';
			
			$this->_link = mysql_connect($host, $user, $pass);
			mysql_select_db('user_info', $this->_link);
			
			/*
			$err = mysql_error();
			if(isset($err))
				{
					print($err);
					die();
			    }*/
		}
		
		public function getLink()
		{
			return $this->_link;
		}
	};
?>