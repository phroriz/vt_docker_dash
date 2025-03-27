<?php
namespace core;

use \src\Config;

class Database {
    private static $_connections = [];
    private static $_pdo;
    public static function getInstance() {
        if (!isset(self::$_pdo)) {
            self::$_pdo = new \PDO(
                Config::DB_DRIVER . ":dbname=" . Config::DB_DATABASE . ";host=" . Config::DB_HOST,
                Config::DB_USER,
                Config::DB_PASS
            );
            self::$_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return self::$_pdo;
    }
    public static function getConnection($dbName = 'DB1') {
        if (!isset(self::$_connections[$dbName])) {
            $config = constant("src\Config::{$dbName}");
            if (!$config) {
                throw new \Exception("Configuração do banco $dbName não encontrada.");
            }

            self::$_connections[$dbName] = new \PDO(
                "{$config['driver']}:dbname={$config['database']};host={$config['host']}",
                $config['user'],
                $config['password']
            );
            self::$_connections[$dbName]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$_connections[$dbName];
    }

    private function __construct() { }
    private function __clone() { }
    public function __wakeup() { }
}