CREATE DATABASE IF NOT EXISTS ewallet_db;
USE ewallet_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    points INT DEFAULT 0,
    last_checkin DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS rewards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    cost INT NOT NULL,
    image_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('earn', 'redeem') NOT NULL,
    amount INT NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Sample Users (password is 'password123' hashed)
INSERT INTO users (username, email, password, points) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 500),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1200);

-- Sample Rewards
INSERT INTO rewards (name, description, cost, image_url) VALUES
('Amazon Gift Card $10', 'Redeem for a $10 Amazon Gift Card', 1000, 'https://placehold.co/100x100?text=Amazon'),
('Starbucks Coffee', 'Get a free tall coffee', 500, 'https://placehold.co/100x100?text=Starbucks'),
('Movie Ticket', 'One standard movie ticket', 800, 'https://placehold.co/100x100?text=Movie');
