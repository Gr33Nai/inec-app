<?php
class Database {
    private $host = 'mysql';
    private $db_name = 'bincomphptest';
    private $username = 'inec_user';
    private $password = 'kali';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Try to connect with retry logic for Docker
        $max_retries = 5;
        $retry_count = 0;
        
        while ($retry_count < $max_retries) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", 
                    $this->username, 
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                
                // Connection successful, break out of retry loop
                break;
                
            } catch(PDOException $exception) {
                $retry_count++;
                
                if ($retry_count >= $max_retries) {
                    // Final attempt failed, show error
                    error_log("Database connection error: " . $exception->getMessage());
                    
                    // For development, show detailed error
                    if (getenv('APP_ENV') === 'development') {
                        die("Database connection failed: " . $exception->getMessage() . 
                            ". Retried " . $max_retries . " times.");
                    } else {
                        die("Database connection failed. Please try again later.");
                    }
                }
                
                // Wait before retrying (exponential backoff)
                sleep(pow(2, $retry_count));
            }
        }
        
        return $this->conn;
    }

    // Test connection method
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn) {
                $stmt = $conn->query("SELECT 1");
                return $stmt->fetch() ? true : false;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
