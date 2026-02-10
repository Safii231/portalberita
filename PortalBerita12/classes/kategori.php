<?php
require_once __DIR__ . '/../config/koneksi.php';

class Kategori extends Database {
    public function getAll() {
        return $this->conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM kategori WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insert($nama) {
        $stmt = $this->conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $nama);
        return $stmt->execute();
    }

    public function update($id, $nama) {
        $stmt = $this->conn->prepare("UPDATE kategori SET nama_kategori = ? WHERE id = ?");
        $stmt->bind_param("si", $nama, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM kategori WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>