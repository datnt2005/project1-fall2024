
<?php

/**
 * chuyen thanh class
 * 
 */

class Database
{
    private $host;
    private $username;
    private $password;
    private $connection;
    private $dbname;
    public function __construct($host, $username, $password, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connection = null;
    }
    public function getConnection()
    {
        try {
            $url = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $this->connection = new PDO($url, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connection successful";
            return $this->connection;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}
