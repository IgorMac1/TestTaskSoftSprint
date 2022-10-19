<?php

namespace app\model;

use core\Model;

class User extends Model
{
    const ADMIN_ROLE_ID = 2;
    const USER_ROLE_ID = 1;

    protected $fillableFields = [
        'name',
        'surname',
        'role_id',
        'status'
    ];

    public function addNewUser()
    {
        $data = getRequestData();
        $params = [
            'name' => htmlspecialchars($data['name']),
            'surname' => htmlspecialchars($data['surname']),
            'role_id' => in_array($data['role_id'], [self::ADMIN_ROLE_ID, self::USER_ROLE_ID]) ? $data['role_id'] : self::USER_ROLE_ID,
            'status' => empty($data['status']) ? 'off' : htmlspecialchars($data['status']),
        ];
        $result = $this->db->query("INSERT INTO users (name, surname, role_id, status) VALUES (:name, :surname, :role_id, :status)", $params);
        if ($result) {
            $newUserId = $this->db->getLastInsertId();
            if ($newUserId) {
                return $this->getUser($newUserId);
            }
        }

        return null;
    }

    public function getAllUsers()
    {
        return $this->db->getAllRows('SELECT users.id,
                                                 users.name, 
                                                 users.surname,
                                                 CONCAT(users.name, " ", users.surname) full_name,
                                                 status,
                                                 role_id,
                                                 roles.name role
                                            FROM users
                                       LEFT JOIN roles ON roles.id = users.role_id
                                        ORDER BY users.id');
    }

    public function getUser($id)
    {
        return $this->db->row('SELECT users.id, 
                                         users.name, 
                                         users.surname,
                                         CONCAT(users.name, " ", users.surname) full_name,
                                         status,
                                         role_id,
                                         roles.name role
                                    FROM users
                               LEFT JOIN roles ON roles.id = users.role_id
                                   WHERE users.id = :id', ['id' => $id]);
    }

    public function editUser()
    {
        $id = getRequestId();
        if ($id) {
            $user = $this->getUser($id);
            if (!$user) {
                return null;
            }
        }

        $userData = getRequestData();
        $updateFields = [];
        $bindParams = [];

        foreach ($userData as $key => $field) {
            if (in_array($key, $this->fillableFields)) {
                $updateFields[] = $key . ' = ' . ':' . $key;
                $bindParams[$key] = $field;
            }
        }
        $bindParams['id'] = $id;
        $sql = "UPDATE users SET " . implode(',', $updateFields) . " WHERE id = :id";
        $result = $this->db->query($sql, $bindParams);
        if ($result) {
            return $this->getUser($id);
        }

        return null;
    }


    public function deleteUser($id)
    {
        if ($id) {
            $user = $this->getUser($id);
            if (!$user) {
                return null;
            }
        }
        return $this->db->query("DELETE FROM users WHERE id = :id ", ['id' => $id]);
    }

    public function changeUserStatus()
    {
        $postData = getRequestData();
        $result = null;

        if (empty($postData['status'])) {
            return ['message' => 'Please provide correct status'];
        }
        if (empty($postData['ids'])) {
            return ['message' => 'Please provide user ids'];
        }

        if ($postData['status'] === 'active') {
            foreach ($postData['ids'] as $id) {
                $result = $this->db->query("UPDATE users SET status = 'on' WHERE id = :id ", ['id' => $id]);
            }
        }

        if ($postData['status'] === 'inactive') {
            foreach ($postData['ids'] as $id) {
                $result = $this->db->query("UPDATE users SET status = 'off' WHERE id = :id ", ['id' => $id]);
            }
        }

        return $result ? ['message' => ''] : ['message' => 'DB error'];
    }

}




