CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    role ENUM('admin','penulis') DEFAULT 'penulis'
);

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100)
);

CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200),
    konten TEXT,
    foto VARCHAR(100),
    kategori_id INT,
    user_id INT,
    tanggal DATETIME,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, password, role) VALUES
('SAFII','SAFII123','admin'),
('AHMAD','AHMAD123','penulis');