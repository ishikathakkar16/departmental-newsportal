



-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'user') NOT NULL
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category_id INT,
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Comments table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE posts
ADD description TEXT NOT NULL,
ADD image VARCHAR(255) DEFAULT NULL;

INSERT INTO users (username, password, role) 
VALUES 
('xyz', '$2y$10$kL0JzDkgJNYAB1Z2/NIcLOH2SY08cvxJ0OlFiT7gqGqEqE.1I8T.G', 'admin'); -- Password: admin123

-- Insert Default User
INSERT INTO users (username, password, role) 
VALUES 
('abc', '$2y$10$S1iMlixI7zQ0D1Gd08mUyuv1GpUL1MhZy/v9Ow/lk2z0AmXvEBdui', 'user'); -- Password: user123

-- Insert Default Categories
INSERT INTO categories (name) 
VALUES 
('Technology'),
('General'),
('Entertainment');

INSERT INTO posts (title, content, category_id, author_id, description, image) 
VALUES 
('Gujarat', 'Gujarat University ', 2, 1, 'The Gujarat University is a public state university located at Ahmedabad, Gujarat, India. The university is an affiliating university at the under-graduate level and a teaching university at the post graduate level. It is accredited B++ by NAAC. It was established on 23 November 1949. ', 'admin/uploads/example-image.jpeg');


