<?php

namespace Config;

use PDO;
use PDOException;
use Exception;

class Database {
    private static $instance = null;
    private $conn = null;
    
    private const HOST = "localhost";
    private const DB_NAME = "starwars_db";
    private const USERNAME = "root";
    private const PASSWORD = "root";
    private const PORT = "3306";

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . self::HOST . 
                    ";port=" . self::PORT .
                    ";dbname=" . self::DB_NAME . 
                    ";charset=utf8mb4",
                    self::USERNAME,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                );
            } catch (PDOException $e) {
                error_log("Erro de conexão: " . $e->getMessage());
                throw new Exception("Falha na conexão com o banco de dados");
            }
        }
        return $this->conn;
    }

    private function __clone() {}
    private function __wakeup() {}
}