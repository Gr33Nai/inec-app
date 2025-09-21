#!/bin/bash

# INEC Application Setup Script
set -e

echo "=========================================="
echo "INEC Polling Unit Results System Setup"
echo "=========================================="

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "Docker Compose is not installed. Please install Docker Compose."
    exit 1
fi

# Create necessary directories
echo "Creating directories..."
mkdir -p logs/nginx
mkdir -p inc/config

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 .
chmod +x *.sh

# Check if database.php exists, if not create it
if [ ! -f "inc/config/database.php" ]; then
    echo "Creating database configuration..."
    cat > inc/config/database.php << 'EOF'
<?php
class Database {
    private $host = 'mysql';
    private $db_name = 'bincomphptest';
    private $username = 'inec_user';
    private $password = 'kali';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            throw $exception;
        }
        return $this->conn;
    }
}
?>
EOF
fi

# Build and start Docker containers
echo "Building and starting Docker containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! docker-compose exec mysql mysqladmin ping -h localhost -u root -prootpassword --silent; do
    sleep 5
done

# Wait a bit more for the database to be fully initialized
sleep 10

# Check if database was created successfully
echo "Checking database setup..."
if docker-compose exec mysql mysql -u inec_user -pkali -e "USE bincomphptest; SHOW TABLES;" | grep -q "agentname"; then
    echo "✓ Database setup completed successfully!"
else
    echo "✗ Database setup failed. Please check the MySQL logs."
    exit 1
fi

# Display access information
echo ""
echo "=========================================="
echo "Setup Completed Successfully!"
echo "=========================================="
echo "Application: http://localhost"
echo "PHPMyAdmin: http://localhost:8080"
echo ""
echo "PHPMyAdmin Credentials:"
echo "Username: root"
echo "Password: rootpassword"
echo ""
echo "Application Database Credentials:"
echo "Username: inec_user"
echo "Password: kali"
echo "Database: bincomphptest"
echo ""
echo "To view logs: docker-compose logs -f"
echo "To stop: docker-compose down"
echo "=========================================="
