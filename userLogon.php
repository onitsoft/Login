<?php

	class userLogon
	{
		private $_pdo;
		private $_uName;
		private $_uPass;
		private $_id;
		//private $_cookie;

		public function __construct($pdo)
		{
			session_start();
			$this->_pdo = $pdo;

			if(isset($_SESSION['uName'], $_SESSION['uPass']))
			{
				$this->_uName = $_SESSION['uName'];
				$this->_uPass = $_SESSION['uPass'];
			}

			else if(isset($_COOKIE['uName'], $_COKIE['uPass']))
			{
				$qres = $this->authUser($uName, md5($uPass));
				$numRows = mysql_num_rows($qres);

				if($numRows == 0)
				{
					header("Location:index.php");
				}

				else if($numRows == false)
					die("WTF?!");//header("Location:index.php");

				else
				{
					$_uName = $_COOKIE['uName'];
					$_uPass = $_COOKIE['uPass'];
					startSession();
				}
			}

		}

		private function authUser($un, $pass)
		{
			$un = md5($un);
			$query = "SELECT id, cookie FROM users WHERE MD5(uName) = '" .$un. "'" . "AND uPass = '" . $pass . "' LIMIT 1";
			$qres = mysql_query($query, $this->_pdo);

			return $qres;
		}

		private function startSession()
		{
		    $_SESSION['uName'] = $this->_uName;
			$_SESSION['uPass'] = $this->_uPass;
			$_SESSION['count'] = 0;
			$_SESSION['uAgent'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['uAddr'] = $_SERVER['REMOTE_ADDR'];
		}

		private function endSession()
		{
			session_end();
		}

		private function  validateSession()
		{
			if($_SERVER['HTTP_USER_AGENT'] != $_SESSION['uAgent'] and $_SERVER['REMOTE_ADDR'] != $_SESSION['uAddr'])
			{
				endSession(); // same as session_destroy();
				killCookie();
				header('Location:login.php');
				return false;
			}

			if($_SESSION['count'] >= 5)
				session_regenerate_id();
			else
			   	$_SESSION['count'] +=1;

			return true;
		}

		private function makeCookies()
		{
			setcookie("uName", $this->_uName, time() + (3600 * 24), '/', 'localhost');
			setcookie("uPass", $this->_uPass, time() + (3600 * 24), '/', 'localhost');

			//echo "Cookies: " . " " . $_COOKIE['uName'] . " " . $_COOKIE['uPass'];// debugging cookies mechanism
		}

		private function killCookies()
		{
			setcookie("uName", $this->_uName, time() - 3600, '/', 'localhost');
			setcookie("uPass", $this->_uPass, time() - 3600), '/', 'localhost';
		}

		public function isLoggedIn()
		{
			if($this->_uName)
				return true;
			return false;
		}



		public function login($uName, $uPass, $cookie)
		{
			$qres = $this->authUser(md5($uName), md5($uPass)); //sanitizng user name input

			if(mysql_num_rows($qres) == 0)
				return false;

			$this->_uName = $uName;
			$this->_uPass = $uPass;

			$row = mysql_fetch_assoc($qres);
			$this->_id = $row['id'];

			if($cookie == 1)
			{
				$this->makeCookies();
			}
			else
			    $this->killCookies();

			if($row['cookie'] != $cookie)
			{
                if($cookie == 1 or $cookie == 'on' or $cookie === true)
                   $cookie = 1;
                elseif($cookie == 0 or $cookie == 'off' or $cookie === false)
                    $cookie = 0;
                 else
                    $cookie = 0; //defualt
				$query = "UPDATE users SET cookie = '" . $cookie . "' WHERE id = '" . $this->_id . "'";
				mysql_query($query, $this->_pdo);
			}

			$this->startSession();

			return true;
		}

		public function logOut()
		{
			if(isset($_COOKIE['uName'], $_COKIE['uPass']))
				killCookies();
			if(isset($_SESION))
				endSession();
			header('Location:login.php');
		}

		public function getUserName()
		{
			return $this->_uName;
		}
	}
?>