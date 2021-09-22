<?php 
class Database {

	public 	$connection;
	private $engine;
	private $host;
	private $username;
	private $database;
	private $password;

	public function __construct() {

		

		//	 $this->engine 	= 'mysql';
		//	 $this->host 	= 'localhost';
		//	 $this->database = 'omni_app';
		//	 $this->username = 'root';
		// $this->password = 'root';


		 	
		
		     $this->engine 	= 'mysql';
		 	 $this->host 	= 'db-mysql-sgp1-11187-do-user-7996996-0.b.db.ondigitalocean.com:25060';
		 	 $this->database = 'omni_ticketing';
		 	 $this->username = 'admin_usa1';
		 	 $this->password = 'mntsdc3krnstilcc';
		
			/*   $this->engine 	= 'mysql';
		 	 $this->host 	= 'assaabloy-do-user-7996996-0.b.db.ondigitalocean.com:25060';
		 	 $this->database = 'assaabloycc_new';
		 	 $this->username = 'doadmin';
		 	 $this->password = 'aggibgqsor10xpdy';*/

		$dns = $this->engine . ":" . "host=" . $this->host . ";dbname=" . $this->database ;

		try {
			$this->connection = new \PDO($dns, $this->username, $this->password);
			
		}
		catch ( PDOException $e ) {
			$e->getMessage();
		}
		if($this->connection) {
			return $this->connection;
		}
	}
	
	
	 function errorLog($file_name, $message){

                $time = date("Y-m-d H:i:s");
                $ipaddress = $_SERVER['REMOTE_ADDR'];
                $log_data =  $message." on ".$time." through - (".$ipaddress."). User IP - ".$_SERVER['HTTP_X_SUCURI_CLIENTIP'];
                $log = file_put_contents($file_name.'.txt', $log_data.PHP_EOL , FILE_APPEND | LOCK_EX);

        }

}
 define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']."/");
 define('API_ROOT_PATH', DOCUMENT_ROOT."api/v1.0/");
 define('STORAGE_PATH', DOCUMENT_ROOT.'api/storage/');
 define('CONTROLLER_PATH', API_ROOT_PATH.'services/controllers/');
 define('APPLICATION_PATH', API_ROOT_PATH.'services/application/');


//define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']."/");
//define('API_ROOT_PATH', DOCUMENT_ROOT."projects/omni/omni_bus/api/v1.0/");
//define('STORAGE_PATH', DOCUMENT_ROOT.'projects/omni/omni_bus/api/storage/');
//define('CONTROLLER_PATH', API_ROOT_PATH.'services/controllers/');
//define('APPLICATION_PATH', API_ROOT_PATH.'services/application/');
