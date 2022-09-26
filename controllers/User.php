<?php

class User {
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
        $sql = "SELECT id, first_name, last_name, email_address, phone_number FROM users WHERE id= ".$id;
        $record = $this->database->getRecord($sql);
        echo json_encode($record);
    }

    public function getAll() {
        $sql = "SELECT id, first_name, last_name, email_address, phone_number FROM users";
        $data = $this->database->getAll($sql);
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
        $createdUser = $this->database->createUser($data);
        echo json_encode($createdUser);
    }

    private function getValidationErrors($data) {
        $errors = [];
        if(empty($data["first_name"])) {
            $errors[] = "First name is required";
        }
        if(empty($data["last_name"])) {
            $errors[] = "Last name is required";
        }
        if(empty($data["password"])) {
            $errors[] = "Password is required";
        }
        if(empty($data["email_address"])) {
            $errors[] = "Email address is required";
        }
        return $errors;
    }

}

?>