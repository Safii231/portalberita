<?php
require_once __DIR__ . '/../config/koneksi.php';

class Berita extends Database {

    public function getAll() {
        $sql = "SELECT b.*, k.nama_kategori, u.username
                FROM berita b
                JOIN kategori k ON b.kategori_id=k.id
                JOIN users u ON b.user_id=u.id
                ORDER BY b.tanggal DESC";
        return $this->conn->query($sql);
    }

    public function getAllByUser($user_id) {
        $stmt = $this->conn->prepare("SELECT b.*, k.nama_kategori AS kategori, u.username AS penulis
                                      FROM berita b
                                      LEFT JOIN kategori k ON b.kategori_id = k.id
                                      LEFT JOIN users u ON b.user_id = u.id
                                      WHERE b.user_id = ?
                                      ORDER BY b.tanggal DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Ambil detail satu berita
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT b.*, k.nama_kategori AS kategori, u.username AS penulis
                                      FROM berita b
                                      LEFT JOIN kategori k ON b.kategori_id = k.id
                                      LEFT JOIN users u ON b.user_id = u.id
                                      WHERE b.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insert($judul, $konten, $foto, $kategori_id, $user_id) {
        $stmt = $this->conn->prepare("INSERT INTO berita (judul, konten, foto, kategori_id, user_id, tanggal) 
                                      VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssii", $judul, $konten, $foto, $kategori_id, $user_id);
        return $stmt->execute();
    }

    public function getByKategori($id) {
        $stmt = $this->conn->prepare("SELECT * FROM berita WHERE kategori_id=? ORDER BY tanggal DESC");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDetail($id) {
        $stmt = $this->conn->prepare("SELECT * FROM berita WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $judul, $konten, $kategori_id, $foto=null, $user_id=null) {
        if ($foto) {
            $stmt = $this->conn->prepare("UPDATE berita SET judul=?, konten=?, kategori_id=?, foto=? WHERE id=?");
            $stmt->bind_param("ssisi", $judul, $konten, $kategori_id, $foto, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE berita SET judul=?, konten=?, kategori_id=? WHERE id=?");
            $stmt->bind_param("ssii", $judul, $konten, $kategori_id, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        // ambil nama file sebelum dihapus supaya bisa unlink
        $stmt = $this->conn->prepare("SELECT foto FROM berita WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $foto = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $stmt2 = $this->conn->prepare("DELETE FROM berita WHERE id = ?");
        $stmt2->bind_param("i", $id);
        $ok = $stmt2->execute();

        if ($ok && $foto) {
            $file = __DIR__ . '/../assets/upload/' . $foto['foto'];
            if (file_exists($file)) unlink($file);
        }

        return $ok;
    }
}
?>