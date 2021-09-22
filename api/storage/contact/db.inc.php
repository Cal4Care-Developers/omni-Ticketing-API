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
		 	 $this->host 	= 'erpcal4care-migrated.cesb3yl7kjik.ap-southeast-1.rds.amazonaws.com';
		 	 $this->database = 'omni_new';
		 	 $this->username = 'cal4care';
		 	 $this->password = '?uniquE123';
		

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
