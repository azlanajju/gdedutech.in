-- Main Blog Table
CREATE TABLE Blogs (
    blog_id INT PRIMARY KEY AUTO_INCREMENT,
    main_cover_image VARCHAR(255),  -- Main blog cover image URL or file path
    title VARCHAR(255) NOT NULL,
    content TEXT,  -- Optional main content/intro
    author_id INT NOT NULL, -- Optional reference to Users table
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES Users(user_id) ON DELETE SET NULL
);

-- Blog Subsections Table
CREATE TABLE BlogSections (
    section_id INT PRIMARY KEY AUTO_INCREMENT,
    blog_id INT NOT NULL,
    title VARCHAR(255),  -- Subtitle
    content TEXT,                 -- Sub content
    image VARCHAR(255),           -- Optional image for the section
    section_order INT DEFAULT 0,  -- For ordering sections
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES Blogs(blog_id) ON DELETE CASCADE
);
