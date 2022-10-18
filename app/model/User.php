<?php

namespace app\model;

use core\Model;

class User extends Model
{

    public function addNewUser(array $data): object
    {
        if (!isset($data['status'])) {
            $data['status'] = 'off';
        }
        $params = [
            'name' => htmlspecialchars(strtolower($data['name'])),
            'surname' => htmlspecialchars(strtolower($data['surname'])),
            'role' => htmlspecialchars(strtolower($data['role'])),
            'status' => htmlspecialchars(strtolower($data['status'])),

        ];
        return $this->db->query("INSERT INTO users (name,surname,is_admin,status) VALUES (:name,:surname,:role,:status)", $params);

    }


    public function getAllUsers()
    {
        return $this->db->getAllRows('SELECT * FROM users');

    }

    public function editUser()
    {


//        return $this->db->row('UPDATE')
    }


    public function deleteUsers($id): object
    {

        $params = [
            'id' => $id
        ];


        return $this->db->query("DELETE FROM users WHERE id = :id ", $params);


    }

    public function setActive($id): object
    {
        $params = [
            'id' => $id
        ];

        return $this->db->query("UPDATE users SET status = 'on' WHERE id = :id ", $params);

    }


    public function setNotActive($id): object
    {
        $params = [
            'id' => $id
        ];

        return $this->db->query("UPDATE users SET status = 'off' WHERE id = :id ", $params);

    }



//    public function setActive($data)
//    {
//        $params = [
//            'id' => $data['id']
//        ];
//        foreach ($data['id'] as $value){
//
//            $this->db->row("UPDATE users SET status = 'on' WHERE id = '$value' ",$params);
//        }
//        return true;
//
//    }

//    public function setNotActive($data)
//    {
//        $params = [
//            'id' => $data['id']
//        ];
//        foreach ($data['id'] as $value){
//
//            $this->db->row("UPDATE users SET status = 'off' WHERE id = '$value' ",$params);
//        }
//
//    }


}




