
CREATE DATABASE fixmate_db;

USE fixmate_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(20),
    verified INT DEFAULT 0
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    homeowner_id INT,
    title VARCHAR(100),
    description TEXT,
    location VARCHAR(150),
    status VARCHAR(30) DEFAULT 'Open',
    FOREIGN KEY (homeowner_id) REFERENCES users(id)
);

CREATE TABLE quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    repairman_id INT,
    price DECIMAL(10,2),
    message TEXT,
    status VARCHAR(30) DEFAULT 'Pending',
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (repairman_id) REFERENCES users(id)
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    homeowner_id INT,
    repairman_id INT,
    price DECIMAL(10,2),
    status VARCHAR(30) DEFAULT 'Booked',
    payment_status VARCHAR(30) DEFAULT 'Unpaid',
    FOREIGN KEY (job_id) REFERENCES jobs(id),
    FOREIGN KEY (homeowner_id) REFERENCES users(id),
    FOREIGN KEY (repairman_id) REFERENCES users(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    amount DECIMAL(10,2),
    payment_status VARCHAR(30) DEFAULT 'Paid',
    payment_date DATE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
