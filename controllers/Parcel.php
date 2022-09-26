<?php

class Parcel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function processRequest($method, $id) {
        switch($method) {
            case "GET":
                $this->get($id);
                break;
            case "POST":
                $this->post();
                break;
        }
    }

    public function get($id) {
        if($id) {
            $this->getRecord($id);
            return;
        } 
        else {
            $this->getAll();
        }
    }

    public function getRecord($id) {
        $sql = "SELECT id, parcel_number, size, user_id FROM parcels WHERE id= ".$id;
        $record = $this->database->getRecord($sql);
        $user_id = $record["user_id"];
        $user = $this->database->getRecord(
            "SELECT id, first_name, last_name, email_address, phone_number 
            FROM users WHERE id= ".$user_id);
        echo json_encode([
            "id" => $record["id"],
            "parcel_number" => $record["parcel_number"],
            "size" => $record["size"],
            "user" => $user
        ]);
    }

    public function getAll() {
        $sql = "SELECT id, parcel_number, size, user_id FROM parcels";
        $parcels = $this->database->getAll($sql);
        $data = [];
        foreach($parcels as $parcel) {
            $user = $this->database->getRecord(
                "SELECT id, first_name, last_name, email_address, phone_number 
                FROM users WHERE id= ".$parcel["user_id"]);
            $data[] = [
                "id" => $parcel["id"],
                "parcel_number" => $parcel["parcel_number"],
                "size" => $parcel["size"],
                'user' => $user
             ];
        }
        echo json_encode($data);
    }

    public function post() {
        $data = (array) json_decode(file_get_contents("php://input"), true);

        $errors = $this->getValidationErrors($data);
        if(!empty($errors)) {
            http_response_code(422);
            echo json_encode(['errors'=>$errors]);
            return;
        }

        $createdParcel = $this->database->createParcel($data);
        echo json_encode($createdParcel);
    }

    public function getValidationErrors($data) {
        $errors = [];
        $sizes = array("S","M","L","XL");
        if(!in_array($data["size"], $sizes)) {
            $errors[] = "Invalid size format";
        }
    }

}

?>