<?php 

$dsn = 'mysql:dbname=mysql;host=db';
$dbuser = 'root';
$dbpass = 'pass';
$pdo;

try {
  if (!isset($pdo)) {
    $pdo = new PDO(
      $dsn,
      $dbuser,
      $dbpass,
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]
    );
  }
} catch(PDOException $e) {
  echo('Error:'.$e->getMessage().PHP_EOL);
  exit;
}
