<?php
require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

// Load environment variables from .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class DatabaseConnection
{
    private $servername;
    private $username;
    private $password;
    private $database;

    // Constructor reads values ONLY from .env
    public function __construct()
    {
        $this->servername = $_ENV['DB_HOST'];
        $this->username   = $_ENV['DB_USERNAME'];
        $this->password   = $_ENV['DB_PASSWORD'];
        $this->database   = $_ENV['DB_NAME'];
    }

    // Connect method
    public function connect()
    {
        $conn = mysqli_connect(
            $this->servername,
            $this->username,
            $this->password,
            $this->database
        );

        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        return $conn;
    }
}
?>