<?php
abstract class Connexion {
protected $pdo;
function __construct()
{
	try {
		$dsn = 'mysql:host=localhost;dbname=shopping;charset=utf8mb4';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_PERSISTENT => false,
		);
		$this->pdo = new PDO($dsn, 'root', '', $options);
	} catch (PDOException $e) {
		$this->showDatabaseErrorPage();
	}
}
function __destruct()
{
$this->pdo=null;
}




protected function showDatabaseErrorPage()
{
	if (!headers_sent()) {
		http_response_code(505);
	}

	$errorPage = __DIR__ . '/../view/public/505.php';
	if (file_exists($errorPage)) {
		include $errorPage;
	} else {
		echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>505</title></head><body><h1>505</h1><p>Database unavailable.</p></body></html>';
	}
	exit;
}
}
?>