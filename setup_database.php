<?php
// Database setup script
// Run this file once to create the database and table structure

echo "Setting up database...\n";

try {
    require_once 'config/database.php';
    
    // Create a temporary database connection without specifying database name
    $tempDb = new PDO(
        "mysql:host=172.25.118.195;port=3306",
        "root",
        "RootPassword123!",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Try to create database
    $databaseName = 'nadsoft';
    
    echo "Creating database '$databaseName'...\n";
    $tempDb->exec("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created successfully!\n";
    
    // Connect to the new database
    $tempDb = new PDO(
        "mysql:host=172.25.118.195;port=3306;dbname=$databaseName;charset=utf8",
        "root",
        "RootPassword123!",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Create Members table
    echo "Creating Members table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS `Members` (
        `Id` INT AUTO_INCREMENT PRIMARY KEY,
        `CreatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `Name` VARCHAR(50) NOT NULL UNIQUE,
        `ParentId` INT NULL,
        FOREIGN KEY (`ParentId`) REFERENCES `Members`(`Id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $tempDb->exec($sql);
    echo "Members table created successfully!\n";
    
            // Insert sample data
        echo "Inserting sample data...\n";
        $stmt = $tempDb->query("SELECT COUNT(*) FROM Members");
        if ($stmt->fetchColumn() == 0) {
            $sampleData = [
                ['Name' => 'Abhijeet', 'ParentId' => null],
                ['Name' => 'Sid', 'ParentId' => null],
                ['Name' => 'Sanel', 'ParentId' => null],
                ['Name' => 'Kapil', 'ParentId' => null],
                ['Name' => 'Adam', 'ParentId' => null],
                ['Name' => 'Test User 1', 'ParentId' => null],
                ['Name' => 'Albrito', 'ParentId' => 1], // Under Abhijeet
                ['Name' => 'Raghven', 'ParentId' => 2], // Under Sid
                ['Name' => 'Manjiri', 'ParentId' => 2], // Under Sid
                ['Name' => 'Mohit', 'ParentId' => 3], // Under Sanel
                ['Name' => 'Test User 2', 'ParentId' => 6], // Under Test User 1
                ['Name' => 'Bala', 'ParentId' => 7], // Under Albrito
                ['Name' => 'Sadashiv', 'ParentId' => 7], // Under Albrito
                ['Name' => 'Arvind', 'ParentId' => 8], // Under Raghven
                ['Name' => 'Vasim Kudle', 'ParentId' => 9], // Under Manjiri
                ['Name' => 'Test User 3', 'ParentId' => 11], // Under Test User 2
                ['Name' => 'david', 'ParentId' => 14], // Under Arvind
                ['Name' => 'sarvesh', 'ParentId' => 17], // Under david
                ['Name' => 'anup', 'ParentId' => 18], // Under sarvesh
                ['Name' => 'Sachin', 'ParentId' => 13] // Under Sadashiv
            ];
            
            $insertSql = "INSERT INTO Members (Name, ParentId) VALUES (:name, :parent_id)";
            $stmt = $tempDb->prepare($insertSql);
            
            foreach ($sampleData as $data) {
                $stmt->bindParam(':name', $data['Name'], PDO::PARAM_STR);
                $stmt->bindParam(':parent_id', $data['ParentId'], PDO::PARAM_INT);
                $stmt->execute();
            }
            
            echo "Sample data inserted successfully!\n";
        } else {
            echo "Sample data already exists.\n";
        }
    
    echo "\nDatabase setup completed successfully!\n";
    echo "You can now access the application at: http://localhost/test/nadsoft/\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Please check your MySQL connection settings.\n";
}
?>
