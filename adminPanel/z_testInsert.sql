INSERT INTO Users (username, password_hash, email, first_name, last_name, role, profile_image, status)
VALUES 
('john_doe', 'hashed_password1', 'john.doe@example.com', 'John', 'Doe', 'admin', 'profile1.jpg', 'active'),
('jane_smith', 'hashed_password2', 'jane.smith@example.com', 'Jane', 'Smith', 'staff', 'profile2.jpg', 'active'),
('mark_twain', 'hashed_password3', 'mark.twain@example.com', 'Mark', 'Twain', 'student', NULL, 'active'),
('emma_watson', 'hashed_password4', 'emma.watson@example.com', 'Emma', 'Watson', 'student', 'profile4.jpg', 'banned'),
('alice_wonder', 'hashed_password5', 'alice.wonder@example.com', 'Alice', 'Wonder', 'staff', NULL, 'inactive'),
('bob_builder', 'hashed_password6', 'bob.builder@example.com', 'Bob', 'Builder', 'student', 'profile6.jpg', 'active'),
('mike_tyson', 'hashed_password7', 'mike.tyson@example.com', 'Mike', 'Tyson', 'admin', 'profile7.jpg', 'active'),
('nina_paul', 'hashed_password8', 'nina.paul@example.com', 'Nina', 'Paul', 'staff', NULL, 'active'),
('oliver_stone', 'hashed_password9', 'oliver.stone@example.com', 'Oliver', 'Stone', 'student', 'profile9.jpg', 'active'),
('lucy_sky', 'hashed_password10', 'lucy.sky@example.com', 'Lucy', 'Sky', 'student', NULL, 'inactive');
INSERT INTO Categories (name, description)
VALUES 
('Technology', 'Courses related to technology and programming.'),
('Business', 'Learn about business strategies and management.'),
('Design', 'Courses for graphic and UI/UX design.'),
('Marketing', 'Digital marketing and SEO courses.'),
('Science', 'Topics in physics, chemistry, and biology.'),
('Arts', 'Courses related to creative arts and painting.'),
('Photography', 'Master photography techniques and editing.'),
('Finance', 'Courses on financial literacy and investments.'),
('Health', 'Wellness and fitness topics.'),
('Languages', 'Learn new languages effectively.');
INSERT INTO Courses (title, description, thumbnail, price, language, level, created_by, category_id, course_type, status, isPopular)
VALUES 
('Python Programming', 'Learn Python from scratch.', 'python.jpg', 499.99, 'English', 'beginner', 1, 1, 'Online', 'published', 'Yes'),
('Business Strategies', 'Improve your business skills.', 'business.jpg', 599.99, 'English', 'advanced', 2, 2, 'Online', 'published', 'No'),
('Graphic Design', 'Master graphic design tools.', 'design.jpg', 399.99, 'English', 'intermediate', 3, 3, 'Online', 'draft', 'Yes'),
('SEO Fundamentals', 'Learn SEO basics.', 'seo.jpg', 299.99, 'English', 'beginner', 4, 4, 'Self-paced', 'published', 'No'),
('Physics 101', 'Introduction to physics.', 'physics.jpg', 199.99, 'English', 'beginner', 5, 5, 'Online', 'draft', 'No'),
('Creative Writing', 'Unleash your creativity.', 'writing.jpg', 249.99, 'English', 'intermediate', 6, 6, 'Self-paced', 'published', 'No'),
('Photography Basics', 'Learn photography.', 'photo.jpg', 349.99, 'English', 'beginner', 7, 7, 'Workshop', 'published', 'Yes'),
('Stock Market 101', 'Introduction to stocks.', 'finance.jpg', 599.99, 'English', 'beginner', 8, 8, 'Online', 'published', 'No'),
('Yoga and Wellness', 'Achieve a balanced life.', 'health.jpg', 199.99, 'English', 'beginner', 9, 9, 'Self-paced', 'draft', 'No'),
('French for Beginners', 'Start learning French.', 'french.jpg', 299.99, 'English', 'beginner', 10, 10, 'Online', 'published', 'Yes');
INSERT INTO Lessons (course_id, title, description, lesson_order)
VALUES 
(1, 'Introduction to Python', 'Getting started with Python.', 1),
(1, 'Variables and Data Types', 'Learn Python basics.', 2),
(2, 'Introduction to Business', 'Overview of strategies.', 1),
(2, 'Analyzing Competitors', 'Understand competition.', 2),
(3, 'Introduction to Design', 'Design fundamentals.', 1),
(3, 'Using Adobe Tools', 'Overview of tools.', 2),
(4, 'SEO Basics', 'Introduction to SEO.', 1),
(5, 'Understanding Physics', 'Learn about motion.', 1),
(6, 'Writing Styles', 'Explore creative writing.', 1),
(7, 'Photography Basics', 'Photography 101.', 1);
INSERT INTO Videos (lesson_id, title, description, video_url, duration, video_order)
VALUES 
(1, 'Welcome to Python', 'Overview of Python.', 'video1.mp4', '00:10:30', 1),
(1, 'Installing Python', 'Setup guide.', 'video2.mp4', '00:15:00', 2),
(2, 'Variables in Python', 'Learn variables.', 'video3.mp4', '00:12:45', 1),
(3, 'Business Overview', 'Basics of business.', 'video4.mp4', '00:09:00', 1),
(4, 'Understanding Design', 'Learn design basics.', 'video5.mp4', '00:14:30', 1),
(5, 'SEO Intro', 'Basics of SEO.', 'video6.mp4', '00:08:50', 1),
(6, 'Physics Introduction', 'Basics of physics.', 'video7.mp4', '00:13:20', 1),
(7, 'Writing Inspiration', 'Boost creativity.', 'video8.mp4', '00:07:40', 1),
(8, 'Photography Intro', 'Basics of cameras.', 'video9.mp4', '00:10:10', 1),
(9, 'Stocks Overview', 'Basics of stocks.', 'video10.mp4', '00:11:00', 1);
INSERT INTO Enrollments (student_id, course_id, purchase_date, payment_status, progress, access_status, completion_status)
VALUES 
(3, 1, '2024-11-20 10:00:00', 'completed', 25.00, 'active', 'pending'),
(4, 2, '2024-11-21 12:00:00', 'pending', 0.00, 'active', 'pending'),
(5, 3, '2024-11-22 09:00:00', 'completed', 50.00, 'active', 'completed'),
(6, 4, '2024-11-23 14:00:00', 'failed', 0.00, 'canceled', 'pending'),
(7, 5, '2024-11-23 08:00:00', 'completed', 75.00, 'expired', 'completed'),
(8, 6, '2024-11-22 16:00:00', 'completed', 100.00, 'active', 'completed'),
(9, 7, '2024-11-22 18:00:00', 'pending', 20.00, 'active', 'pending'),
(10, 8, '2024-11-23 07:30:00', 'completed', 40.00, 'active', 'pending'),
(3, 9, '2024-11-21 11:45:00', 'completed', 10.00, 'expired', 'pending'),
(4, 10, '2024-11-20 15:00:00', 'failed', 0.00, 'canceled', 'pending');
INSERT INTO Transactions (student_id, course_id, amount, payment_date, payment_method)
VALUES 
(3, 1, 499.99, '2024-11-20 10:05:00', 'Credit Card'),
(4, 2, 599.99, '2024-11-21 12:05:00', 'PayPal'),
(5, 3, 399.99, '2024-11-22 09:05:00', 'Debit Card'),
(6, 4, 299.99, '2024-11-23 14:05:00', 'Net Banking'),
(7, 5, 199.99, '2024-11-23 08:05:00', 'Credit Card'),
(8, 6, 249.99, '2024-11-22 16:05:00', 'PayPal'),
(9, 7, 349.99, '2024-11-22 18:05:00', 'Debit Card'),
(10, 8, 599.99, '2024-11-23 07:35:00', 'Net Banking'),
(3, 9, 199.99, '2024-11-21 11:50:00', 'Credit Card'),
(4, 10, 299.99, '2024-11-20 15:05:00', 'PayPal');
INSERT INTO StaffAssignments (staff_id, course_id, role)
VALUES 
(2, 1, 'instructor'),
(2, 2, 'instructor'),
(8, 3, 'assistant'),
(9, 4, 'assistant'),
(2, 5, 'instructor'),
(8, 6, 'assistant'),
(9, 7, 'assistant'),
(2, 8, 'instructor'),
(8, 9, 'assistant'),
(9, 10, 'assistant');
INSERT INTO Reviews (student_id, course_id, rating, comment, date_posted)
VALUES 
(3, 1, 5, 'Excellent course!', '2024-11-22 10:00:00'),
(4, 2, 4, 'Very useful for beginners.', '2024-11-23 11:00:00'),
(5, 3, 3, 'Good, but could be improved.', '2024-11-23 12:00:00'),
(6, 4, 2, 'Not worth the price.', '2024-11-23 13:00:00'),
(7, 5, 5, 'Highly recommended!', '2024-11-23 14:00:00'),
(8, 6, 4, 'Well-structured.', '2024-11-23 15:00:00'),
(9, 7, 3, 'Average content.', '2024-11-23 16:00:00'),
(10, 8, 5, 'Great insights.', '2024-11-23 17:00:00'),
(3, 9, 4, 'Good for the price.', '2024-11-23 18:00:00'),
(4, 10, 5, 'Loved it!', '2024-11-23 19:00:00');
INSERT INTO Quizzes (course_id, title, instructions, total_marks)
VALUES 
(1, 'Python Basics Quiz', 'Answer all questions.', 50),
(2, 'Business Strategies Quiz', 'Complete in 30 minutes.', 40),
(3, 'Design Tools Quiz', 'Multiple-choice questions.', 30),
(4, 'SEO Quiz', 'Test your SEO knowledge.', 20),
(5, 'Physics Fundamentals Quiz', 'Solve all problems.', 50),
(6, 'Creative Writing Quiz', 'Write short essays.', 60),
(7, 'Photography Techniques Quiz', 'Answer theoretical questions.', 40),
(8, 'Stock Market Quiz', 'Analyze case studies.', 70),
(9, 'Wellness Quiz', 'Health-related questions.', 30),
(10, 'French Quiz', 'Test your language skills.', 50);
INSERT INTO Questions (quiz_id, content, option_a, option_b, option_c, option_d, correct_option)
VALUES 
(1, 'What is Python?', 'A snake', 'A programming language', 'A fruit', 'None of these', 'B'),
(1, 'What is a variable?', 'A constant', 'A storage container', 'A loop', 'None of these', 'B'),
(2, 'What is strategy?', 'A tactic', 'A plan', 'A goal', 'None of these', 'B'),
(2, 'What is SWOT analysis?', 'A method', 'An analysis', 'A report', 'None of these', 'B'),
(3, 'What is Adobe Photoshop?', 'A design tool', 'A game', 'A website', 'None of these', 'A'),
(3, 'What is a vector?', 'A bitmap', 'A scalable graphic', 'An image', 'None of these', 'B'),
(4, 'What is SEO?', 'Search Engine Optimization', 'Social Media Optimization', 'A tool', 'None of these', 'A'),
(4, 'What is a keyword?', 'A tag', 'A word', 'A phrase', 'None of these', 'C'),
(5, 'What is gravity?', 'A force', 'An element', 'A reaction', 'None of these', 'A'),
(5, 'What is acceleration?', 'Change in speed', 'Force', 'Time', 'None of these', 'A');
INSERT INTO Certificates (student_id, course_id, certificate_url, issue_date)
VALUES 
(3, 1, 'certificates/python_cert.jpg', '2024-11-23 10:00:00'),
(4, 2, 'certificates/business_cert.jpg', '2024-11-23 11:00:00'),
(5, 3, 'certificates/design_cert.jpg', '2024-11-23 12:00:00'),
(6, 4, 'certificates/seo_cert.jpg', '2024-11-23 13:00:00'),
(7, 5, 'certificates/physics_cert.jpg', '2024-11-23 14:00:00'),
(8, 6, 'certificates/writing_cert.jpg', '2024-11-23 15:00:00'),
(9, 7, 'certificates/photography_cert.jpg', '2024-11-23 16:00:00'),
(10, 8, 'certificates/stock_cert.jpg', '2024-11-23 17:00:00'),
(3, 9, 'certificates/health_cert.jpg', '2024-11-23 18:00:00'),
(4, 10, 'certificates/french_cert.jpg', '2024-11-23 19:00:00');
