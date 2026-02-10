<?php
require_once __DIR__ . '/../config/koneksi.php';

class User extends Database {

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM users");
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insert($username, $password, $role) {
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
        $stmt->bind_param("sss", $username, $password, $role);
        return $stmt->execute();
    }

    public function update($id, $username, $password, $role) {
        if ($password != "") {
            $stmt = $this->conn->prepare("UPDATE users SET username=?, password=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $password, $role, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
            $stmt->bind_param("ssi", $username, $role, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>