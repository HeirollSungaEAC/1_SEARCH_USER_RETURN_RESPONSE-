CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    address TEXT,
    qualifications TEXT
);


INSERT INTO applicants (first_name, last_name, email, phone, address, qualifications)
VALUES
('Jane', 'Doe', 'jane.doe@example.com', '1234567890', '123 Main St', 'M.Ed, B.Ed'),
('John', 'Smith', 'john.smith@example.com', '9876543210', '456 Elm St', 'B.Ed');
