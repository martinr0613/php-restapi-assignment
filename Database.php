<?php

require_once __DIR__."/config.php";

class Database {

    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=".DB_HOST.
                ";dbname=".DB_DATABASE_NAME.
                ";charset=utf8",
                DB_USERNAME, 
                DB_PASSWORD,
                [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                ]
                );
        } catch(Exception $e) {
            throw new Exception($e->getMessage());  
        }
    }

    public function getRecord($sql) {
        $stmnt = $this->connection->prepare($sql);
        $stmnt->execute();
        $data = $stmnt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getAll($sql) {
        $stmnt = $this->connection->query($sql);
        $data = [];
        while($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function createUser($data) {
        $sql = "INSERT INTO users (`first_name`, `last_name`, `password`, `email_address`, `phone_number`)
        VALUES (:first_name, :last_name, :password, :email_address, :phone_number)";

        $stmnt = $this->connection->prepare($sql);

        $stmnt->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
        $stmnt->bindValue(":last_name", $data["last_name"], PDO::PARAM_STR);
        $stmnt->bindValue(":password", password_hash($data["password"], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmnt->bindValue(":email_address", $data["email_address"], PDO::PARAM_STR);
        $stmnt->bindValue(":phone_number", $data["phone_number"], PDO::PARAM_STR);
        $stmnt->execute();

        $id = $this->connection->lastInsertId();
        $user = $this->getRecord("SELECT id, first_name, last_name, email_address, phone_number FROM users WHERE id= ".$id);
        return $user;
    }

    public function createParcel($data) {
        $sql = "INSERT INTO parcels (`parcel_number`, `size`, `user_id`)
        VALUES (:parcel_number, :size, :user_id)";

        $stmnt = $this->connection->prepare($sql);

        $stmnt->bindValue(":parcel_number", substr(uniqid(), 2, 10), PDO::PARAM_STR);
        $stmnt->bindValue(":size", $data["size"], PDO::PARAM_STR);
        $stmnt->bindValue(":user_id", $data["user_id"], PDO::PARAM_STR);
        $stmnt->execute();

        $id = $this->connection->lastInsertId();
        $user_id = $data["user_id"];

        $parcel = $this->getRecord("SELECT id, parcel_number, size FROM parcels WHERE id= ".$id);
        $user = $this->getRecord("SELECT id, first_name, last_name, email_address, phone_number FROM users WHERE id= ".$user_id);
        $parcel["user"] = $user;
        return $parcel;
    }

}

?>