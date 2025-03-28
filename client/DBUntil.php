<?php
include "Database.php";
define("HOST", "localhost");
define("DB_NAME", "project1-fall2024");
define("USERNAME", "root");
define("PASSWORD", "");
class DBUntil
{
    /**x
     * xay dung ham CRUD
     */
    private $connection = null;
    function __construct()
    {
        $db = new Database(HOST, USERNAME, PASSWORD, DB_NAME);
        $this->connection = $db->getConnection();
    }
    public function execute($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);

        // Sử dụng execute trực tiếp với mảng tham số
        $stmt->execute($params);

        return $stmt->rowCount();  // Trả về số dòng bị ảnh hưởng
    }


    public function select($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
    public function query($sql)
    {
        return $this->connection->query($sql);
    }

    public function insert($table, $data)
    {
        /** 
         *  insert category
         * insert into Category ( name , id ) Values ( "abc" , 1 )
         * table category
         *  ['name' => 'abc', 'id' =>1]
         */
        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        // insert into Category ( name , id ) Values ( ":name" , :id )
        $stmt = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->connection->lastInsertId();
    }

    public function update($table, $data, $condition, $params = [])
    {
        $updateFields = [];

        foreach ($data as $key => $value) {
            $updateFields[] = "$key = :$key";
        }
        $updateFields = implode(", ", $updateFields);
        $sql = "UPDATE $table SET $updateFields WHERE $condition";
        $stmt = $this->connection->prepare($sql);

        // Bind data parameters
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Bind condition parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }


    public function delete($table, $condition)
    {
        $sql = "DELETE FROM $table WHERE $condition";
        var_dump($sql);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
