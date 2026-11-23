CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin_user', '1234');

CREATE TABLE technicians (
    tech_id INT AUTO_INCREMENT PRIMARY KEY,
    tech_name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL
);

INSERT INTO technicians (tech_name, specialty) VALUES 
('Juan Dela Cruz', 'Hardware Repair'),
('Maria Santos', 'Network & Wi-Fi');

CREATE TABLE work_orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    tech_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    issue_description TEXT NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    deleted_by VARCHAR(50) NULL,
    deleted_at DATETIME NULL,
    FOREIGN KEY (tech_id) REFERENCES technicians(tech_id)
);

INSERT INTO work_orders (tech_id, customer_name, issue_description) VALUES 
(1, 'Carlo Reyes', 'Laptop screen flickering'),
(1, 'Ana Lopez', 'Battery not charging'),
(2, 'Mark Gomez', 'Router blinking red light');
