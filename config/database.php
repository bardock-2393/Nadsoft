<?php
/**
 * Database Configuration
 * Contains database connection settings
 */
class DatabaseConfig {
    const DB_HOST = '172.25.118.195';
    const DB_PORT = '3306';
    const DB_USER = 'root';
    const DB_PASSWORD = 'RootPassword123!';
    const DB_NAME = 'nadsoft';
    
    /**
     * Get database connection string
     * @return string
     */
    public static function getConnectionString() {
        return "mysql:host=" . self::DB_HOST . ";port=" . self::DB_PORT . ";dbname=" . self::DB_NAME . ";charset=utf8";
    }
    
    /**
     * Get database connection options
     * @return array
     */
    public static function getConnectionOptions() {
        return array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );
    }
}
?>
