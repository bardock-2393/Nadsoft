<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Database Connection Class
 * Handles PDO database connections using singleton pattern
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        try {
            $this->connection = new PDO(
                DatabaseConfig::getConnectionString(),
                DatabaseConfig::DB_USER,
                DatabaseConfig::DB_PASSWORD,
                DatabaseConfig::getConnectionOptions()
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get singleton instance of Database
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get PDO connection
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization of the instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>
