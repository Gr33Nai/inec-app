#!/bin/bash

# INEC Application - Non-Docker Setup
set -e

echo "=========================================="
echo "INEC Polling Unit Results System - Local Setup"
echo "=========================================="

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    echo "Please do not run this script as root."
    exit 1
fi

# Check system
if [ -f /etc/debian_version ]; then
    # Debian/Ubuntu
    echo "Detected Debian/Ubuntu system"
    PKG_MANAGER="apt"
elif [ -f /etc/redhat-release ]; then
    # RHEL/CentOS
    echo "Detected RHEL/CentOS system"
    PKG_MANAGER="yum"
else
    echo "Unsupported system. Please install manually."
    exit 1
fi

# Install required packages
echo "Installing required packages..."
if [ "$PKG_MANAGER" = "apt" ]; then
    sudo apt update
    sudo apt install -y nginx php-fpm php-mysql php-mbstring php-xml php-curl mysql-server
elif [ "$PKG_MANAGER" = "yum" ]; then
    sudo yum install -y nginx php-fpm php-mysqlnd php-mbstring php-xml php-curl mysql-server
fi

# Start services
echo "Starting services..."
sudo systemctl start nginx
sudo systemctl start php-fpm
sudo systemctl start mysql

# Enable services to start on boot
sudo systemctl enable nginx
sudo systemctl enable php-fpm
sudo systemctl enable mysql

# Setup MySQL
echo "Setting up MySQL database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS bincomphptest;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'inec_user'@'localhost' IDENTIFIED BY 'kali';"
sudo mysql -e "GRANT ALL PRIVILEGES ON bincomphptest.* TO 'inec_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Import database
echo "Importing database..."
if [ -f "bincom_test.sql" ]; then
    mysql -u inec_user -pkali bincomphptest < bincom_test.sql
    echo "✓ Database imported successfully!"
else
    echo "✗ bincom_test.sql file not found!"
    exit 1
fi

# Setup Nginx
echo "Setting up Nginx..."
sudo cp nginx.conf /etc/nginx/sites-available/inec-app
sudo ln -sf /etc/nginx/sites-available/inec-app /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx

# Create database configuration if not exists
mkdir -p inc/config
if [ ! -f "inc/config/database.php" ]; then
    cat > inc/config/database.php << 'EOF'
<?php
class Database {
    private $host = 'localhost';
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

# Set permissions
echo "Setting permissions..."
chmod -R 755 .
chmod +x *.sh

# Test the setup
echo "Testing setup..."
if curl -s http://localhost > /dev/null; then
    echo "✓ Nginx is serving content successfully!"
else
    echo "✗ Nginx is not responding. Please check configuration."
fi

# Display access information
echo ""
echo "=========================================="
echo "Local Setup Completed Successfully!"
echo "=========================================="
echo "Application: http://localhost"
echo ""
echo "Database Credentials:"
echo "Username: inec_user"
echo "Password: kali"
echo "Database: bincomphptest"
echo ""
echo "To stop services:"
echo "  sudo systemctl stop nginx php-fpm mysql"
echo "=========================================="
