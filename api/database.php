<?php

date_default_timezone_set('Asia/Bangkok');

class Database {
	private static $dbName = 'codewnet_jcafet';
	private static $dbHost = 'localhost';
	private static $dbUsername = 'codewnet_jcafet';
	private static $dbUserPassword = 'Quangbao1';
	private static $cont = null;
	public function __construct() {
		die ( 'Init function is not allowed' );
	}
	public static function connect() {
		// One connection through whole application
		if (null == self::$cont) {
			try {
				self::$cont = new PDO ( "mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword );
				self::$cont->query ("set character_set_client='utf8'");
				self::$cont->query ("set character_set_results='utf8'");
				self::$cont->query  ("set collation_connection='utf8_general_ci'");
				self::$cont->query  ("SET time_zone='+07:00';");
				
			} catch ( PDOException $e ) {
				die ( $e->getMessage () );
			}
		}
		return self::$cont;
	}
	public static function disconnect() {
		self::$cont = null;
	}
}
?>