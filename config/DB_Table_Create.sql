CREATE DATABASE helpdesk_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, 
    role ENUM('operator', 'technician', 'admin', 'office') NOT NULL,
    full_name VARCHAR(100),
    contact_phone VARCHAR(15),
    email VARCHAR(100)
);

CREATE TABLE offices (
    office_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    address VARCHAR(255),
    contact_phone VARCHAR(15),
    specialisation VARCHAR(100)
);

CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    office_id INT,
    name VARCHAR(100),
    contact_phone VARCHAR(15),
    email VARCHAR(100),
    start_date DATE,
    end_date DATE,
    job_title VARCHAR(50),
    department VARCHAR(50),
    FOREIGN KEY (office_id) REFERENCES offices(office_id)
);

CREATE TABLE equipment (
    equipment_id INT AUTO_INCREMENT PRIMARY KEY,
    office_id INT,
    serial_number VARCHAR(50),
    equipment_type VARCHAR(50),
    make VARCHAR(50),
    model VARCHAR(50),
    manufacturer VARCHAR(50),
    warranty_expiry_date DATE,
    software_licence_number VARCHAR(50),
    software_type VARCHAR(50),
    FOREIGN KEY (office_id) REFERENCES offices(office_id)
);


CREATE TABLE helpdesk_calls (
    call_id INT AUTO_INCREMENT PRIMARY KEY,
    caller_id INT,
    operator_id INT,
    call_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    problem_description TEXT,
    equipment_id INT,
    operating_system VARCHAR(50),
    software_used VARCHAR(50),
    status ENUM('Open', 'Closed') DEFAULT 'Open',
    FOREIGN KEY (caller_id) REFERENCES employees(employee_id),
    FOREIGN KEY (operator_id) REFERENCES users(user_id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(equipment_id)
);


CREATE TABLE problems (
    problem_id INT AUTO_INCREMENT PRIMARY KEY,
    call_id INT,
    technician_id INT,
    problem_type VARCHAR(100),
    assigned_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolution_time TIMESTAMP NULL,
    resolution_description TEXT,
    time_taken FLOAT,
    status ENUM('Open', 'Closed') DEFAULT 'Open',
    FOREIGN KEY (call_id) REFERENCES helpdesk_calls(call_id),
    FOREIGN KEY (technician_id) REFERENCES users(user_id)
);
