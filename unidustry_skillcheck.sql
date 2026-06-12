-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 09:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unidustry_skillcheck`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500'),
(2, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coding_challenges`
--

CREATE TABLE `coding_challenges` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `difficulty_level` enum('easy','medium','hard') DEFAULT 'easy',
  `points` int(11) DEFAULT 10,
  `sample_input` text DEFAULT NULL,
  `sample_output` text DEFAULT NULL,
  `constraints` text DEFAULT NULL,
  `starter_code` text DEFAULT NULL,
  `test_cases` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`test_cases`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coding_challenges`
--

INSERT INTO `coding_challenges` (`id`, `title`, `description`, `difficulty_level`, `points`, `sample_input`, `sample_output`, `constraints`, `starter_code`, `test_cases`, `created_at`) VALUES
(1, 'Sum of Two Numbers', 'Write a function that takes two integers as input and returns their sum.', 'easy', 10, '5 3', '8', 'The input numbers are between -1000 and 1000.', 'def solve(a, b):\r\n    # Write your code here\r\n    return a + b', NULL, '2026-06-11 10:31:56'),
(2, 'Check Even or Odd', 'Write a program that takes an integer and prints \"Even\" if the number is even, or \"Odd\" if the number is odd.', 'easy', 10, '7', 'Odd', 'The input number is between 1 and 1000.', 'def solve(n):\r\n    # Write your code here\r\n    if n % 2 == 0:\r\n        return \"Even\"\r\n    else:\r\n        return \"Odd\"', NULL, '2026-06-11 10:31:56'),
(3, 'Reverse a String', 'Write a function that takes a string and returns it reversed.', 'easy', 15, 'hello', 'olleh', 'The string length is between 1 and 100 characters.', 'def solve(s):\r\n    # Write your code here\r\n    return s[::-1]', NULL, '2026-06-11 10:31:56'),
(4, 'Find Maximum Number', 'Write a function that takes an array of integers and returns the maximum value.', 'easy', 15, '[3, 7, 2, 9, 1]', '9', 'Array length is between 1 and 100. Values are between -1000 and 1000.', 'def solve(arr):\r\n    # Write your code here\r\n    return max(arr)', NULL, '2026-06-11 10:31:56'),
(5, 'Fahrenheit to Celsius', 'Write a program that converts temperature from Fahrenheit to Celsius. Formula: C = (F - 32) * 5/9', 'easy', 12, '98.6', '37.0', 'Temperature is between -100 and 200 Fahrenheit.', 'def solve(f):\r\n    # Write your code here\r\n    return round((f - 32) * 5/9, 1)', NULL, '2026-06-11 10:31:56'),
(6, 'Factorial Calculator', 'Write a function that calculates the factorial of a given number n (n!). Factorial of 5 = 5 × 4 × 3 × 2 × 1 = 120', 'medium', 25, '5', '120', 'Input number is between 1 and 20.', 'def solve(n):\r\n    # Write your code here\r\n    result = 1\r\n    for i in range(1, n + 1):\r\n        result *= i\r\n    return result', NULL, '2026-06-11 10:31:56'),
(7, 'Palindrome Checker', 'Write a function that checks if a given string is a palindrome (reads the same forwards and backwards). Ignore case sensitivity.', 'medium', 25, 'racecar', 'true', 'String length is between 1 and 1000 characters.', 'def solve(s):\r\n    # Write your code here\r\n    s = s.lower()\r\n    return s == s[::-1]', NULL, '2026-06-11 10:31:56'),
(8, 'Fibonacci Sequence', 'Write a function that returns the nth number in the Fibonacci sequence. The sequence starts: 0, 1, 1, 2, 3, 5, 8, 13...', 'medium', 30, '7', '13', 'Input n is between 1 and 30.', 'def solve(n):\r\n    # Write your code here\r\n    if n <= 1:\r\n        return n\r\n    a, b = 0, 1\r\n    for i in range(2, n + 1):\r\n        a, b = b, a + b\r\n    return b', NULL, '2026-06-11 10:31:56'),
(9, 'Count Vowels in a String', 'Write a function that counts the number of vowels (a, e, i, o, u) in a given string. Count both uppercase and lowercase.', 'medium', 20, 'Hello World', '3', 'String length is between 1 and 1000 characters.', 'def solve(s):\r\n    # Write your code here\r\n    vowels = \"aeiouAEIOU\"\r\n    count = 0\r\n    for char in s:\r\n        if char in vowels:\r\n            count += 1\r\n    return count', NULL, '2026-06-11 10:31:56'),
(10, 'Two Sum Problem', 'Given an array of integers and a target integer, return the indices of two numbers that add up to the target. You may assume exactly one solution exists.', 'hard', 50, 'nums = [2, 7, 11, 15], target = 9', '[0, 1]', 'Array length is between 2 and 1000. Values are between -1000 and 1000.', 'def solve(nums, target):\r\n    # Write your code here\r\n    seen = {}\r\n    for i, num in enumerate(nums):\r\n        complement = target - num\r\n        if complement in seen:\r\n            return [seen[complement], i]\r\n        seen[num] = i\r\n    return []', NULL, '2026-06-11 10:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `coding_submissions`
--

CREATE TABLE `coding_submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `code` text NOT NULL,
  `language` varchar(20) DEFAULT 'python',
  `score` int(11) DEFAULT 0,
  `status` enum('pending','passed','failed') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recommendation` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `category` enum('technical','softskills') NOT NULL,
  `question_type` enum('objective','theoretical','coding','debugging') DEFAULT 'objective',
  `question` text NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `correct_answer` int(11) NOT NULL,
  `expected_answer` text DEFAULT NULL,
  `code_snippet` text DEFAULT NULL,
  `language` varchar(50) DEFAULT 'php',
  `recommendation_type` enum('youtube','w3schools','freecodecamp') DEFAULT NULL,
  `recommendation_title` varchar(255) DEFAULT NULL,
  `recommendation_link` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `category`, `question_type`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `expected_answer`, `code_snippet`, `language`, `recommendation_type`, `recommendation_title`, `recommendation_link`, `created_at`) VALUES
(2, 'technical', 'objective', 'You are developing a website, and users report that clicking the \"Submit\" button does nothing. What should be your FIRST action?', 'Immediately rewrite the entire form code', 'Check the browser console and logs for errors', 'Restart your computer', 'Delete the submit button and create another one', 2, NULL, NULL, 'php', 'youtube', 'JavaScript Debugging for Beginners', '', '2026-06-11 07:44:11'),
(3, 'technical', 'objective', 'A university system stores student information in multiple tables. You notice that a student\'s phone number is duplicated in several places, causing inconsistencies. What is the best solution?', 'Copy the phone number into more tables', 'Normalize the database structure', 'Delete all phone numbers', 'Store phone numbers in a text file', 2, NULL, NULL, 'php', 'w3schools', 'Database Normalization Explained', '', '2026-06-11 07:45:20'),
(4, 'technical', 'objective', 'You receive an email asking you to urgently click a link and enter your company login credentials. What should you do?', 'Click immediately', 'Forward it to everyone', 'Verify the sender and report suspicious activity', 'Post the credentials publicly', 3, NULL, NULL, 'php', 'youtube', 'Introduction to Phishing Attacks', '', '2026-06-11 07:46:16'),
(5, 'technical', 'objective', 'Your team is building a new application. Midway through development, the client requests a major feature change. What is the best approach?', 'Ignore the request', 'Discuss impact, requirements, and update the plan', 'Remove existing features', 'Stop the project entirely', 2, NULL, NULL, 'php', 'freecodecamp', 'Introduction to Agile Software Development', '', '2026-06-11 07:47:04'),
(6, 'technical', 'objective', 'A website loads very slowly because of large image files. What is the most effective solution?', 'Increase image sizes', 'Compress and optimize images', 'Add more images', 'Duplicate existing images', 2, NULL, NULL, 'php', 'youtube', 'Website Performance Optimization', '', '2026-06-11 07:48:02'),
(7, 'softskills', 'objective', 'You are working on a group project and notice that one team member is struggling to complete assigned tasks. What should you do?', 'Ignore them', 'Report them immediately without discussion', 'Offer support and discuss how the team can help', 'Remove them from the project', 3, NULL, NULL, 'php', 'youtube', 'Effective Team Collaboration', '', '2026-06-11 07:48:50'),
(8, 'softskills', 'objective', 'During a presentation, a participant asks a question you do not know the answer to. What is the best response?', 'Invent an answer', 'Ignore the question', 'Acknowledge the question and promise to follow up with accurate information', 'End the presentation immediately', 3, NULL, NULL, 'php', 'youtube', 'Professional Communication Skills', '', '2026-06-11 07:49:49'),
(9, 'softskills', 'objective', 'Your team is divided on an important decision. As team leader, what should you do?', 'Make the decision alone without input', 'Listen to viewpoints and guide the team toward a solution', 'Cancel the project', 'Avoid making any decision', 2, NULL, NULL, 'php', 'freecodecamp', 'Leadership and Decision Making', '', '2026-06-11 07:50:46'),
(10, 'softskills', 'objective', 'Your organization introduces a new software tool that changes how work is done. What is the best approach?', 'Refuse to use it', 'Learn the tool and adapt to the new process', 'Wait for others to learn it first', 'Complain continuously', 2, NULL, NULL, 'php', 'youtube', 'Adaptability in the Workplace', '', '2026-06-11 07:51:45'),
(11, 'softskills', 'objective', 'Two colleagues disagree during a meeting and the discussion becomes heated. What should you do?', 'Encourage the argument', 'Take sides immediately', 'Help refocus the discussion on facts and solutions', 'Leave the meeting', 3, NULL, NULL, 'php', 'youtube', 'Conflict Resolution Skills', '', '2026-06-11 07:52:27'),
(12, 'technical', 'objective', 'What does HTML stand for?', 'Hyper Text Markup Language', 'Home Tool Markup Language', 'High Text Machine Language', 'Hyper Transfer Markup Language', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:44'),
(13, 'technical', 'objective', 'Which CSS property is used to change the text color of an element?', 'text-color', 'color', 'font-color', 'bgcolor', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:44'),
(14, 'technical', 'objective', 'What does PHP stand for?', 'Personal Home Page', 'Preprocessor', 'Hypertext Preprocessor', 'Programming Language', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:44'),
(15, 'technical', 'objective', 'Which SQL statement is used to extract data from a database?', 'SELECT', 'EXTRACT', 'GET', 'OPEN', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(16, 'technical', 'objective', 'What does JavaScript use to declare a variable?', 'var', 'let', 'const', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(17, 'technical', 'objective', 'Which of the following is a valid way to comment in Python?', '// comment', '# comment', '/* comment */', '<!-- comment -->', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(18, 'technical', 'objective', 'What does CSS stand for?', 'Creative Style Sheets', 'Computer Style Sheets', 'Cascading Style Sheets', 'Colorful Style Sheets', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(19, 'technical', 'objective', 'Which tag is used to create a hyperlink in HTML?', '<link>', '<a>', '<href>', '<url>', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(20, 'technical', 'objective', 'What does SQL stand for?', 'Structured Question Language', 'Structured Query Language', 'Simple Query Language', 'Standard Query Language', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(21, 'technical', 'objective', 'Which operator is used for \'not equal to\' in PHP?', '!=', '<>', '!==', 'Both A and B', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(22, 'technical', 'objective', 'What is the correct way to start a session in PHP?', 'session_start()', 'start_session()', 'session_begin()', 'begin_session()', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(23, 'technical', 'objective', 'Which function is used to include a file in PHP?', 'include()', 'require()', 'Both A and B', 'import()', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(24, 'technical', 'objective', 'What does AJAX stand for?', 'Asynchronous JavaScript and XML', 'Advanced JavaScript and XML', 'Asynchronous JSON and XML', 'Active JavaScript and XML', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(25, 'technical', 'objective', 'Which method is used to send data to the server without reloading the page?', 'GET', 'POST', 'AJAX', 'FETCH', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(26, 'technical', 'objective', 'What does API stand for?', 'Application Programming Interface', 'Application Program Interface', 'Advanced Programming Interface', 'Application Protocol Interface', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(27, 'technical', 'objective', 'Which of the following is a NoSQL database?', 'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(28, 'technical', 'objective', 'What is the default port for MySQL?', '3306', '5432', '8080', '80', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:45'),
(29, 'technical', 'objective', 'Which Git command is used to create a new branch?', 'git branch new-branch', 'git checkout -b new-branch', 'git create branch', 'Both A and B', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(30, 'technical', 'objective', 'What does OOP stand for?', 'Object-Oriented Programming', 'Ordered Object Programming', 'Object-Oriented Protocol', 'Object Organization Programming', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(31, 'technical', 'objective', 'Which principle is NOT part of OOP?', 'Inheritance', 'Polymorphism', 'Encapsulation', 'Compilation', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(32, 'technical', 'objective', 'What is the output of 5 + \'5\' in JavaScript?', '10', '55', 'Error', 'Undefined', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(33, 'technical', 'objective', 'Which HTML tag is used to display an image?', '<img>', '<image>', '<src>', '<pic>', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(34, 'technical', 'objective', 'What does JSON stand for?', 'JavaScript Object Notation', 'Java Object Notation', 'JavaScript Oriented Notation', 'Java Script Object Notation', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(35, 'technical', 'objective', 'Which HTTP method is used to retrieve data from a server?', 'POST', 'GET', 'PUT', 'DELETE', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(36, 'technical', 'objective', 'What does CRUD stand for?', 'Create, Read, Update, Delete', 'Create, Retrieve, Update, Delete', 'Create, Read, Upload, Delete', 'Create, Read, Update, Drop', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(37, 'technical', 'objective', 'Which of the following is a programming paradigm?', 'Procedural', 'Object-Oriented', 'Functional', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(38, 'technical', 'objective', 'What does IDE stand for?', 'Integrated Development Environment', 'Integrated Design Environment', 'Internal Development Environment', 'Integrated Data Environment', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(39, 'technical', 'objective', 'Which of the following is a version control system?', 'Git', 'SVN', 'Mercurial', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(40, 'technical', 'objective', 'What does DNS stand for?', 'Domain Name System', 'Data Name System', 'Domain Network System', 'Digital Name System', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(41, 'technical', 'objective', 'Which protocol is used for secure communication over the internet?', 'HTTP', 'FTP', 'HTTPS', 'SMTP', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(42, 'technical', 'objective', 'What is the primary key in a database?', 'Unique identifier for a record', 'Foreign key reference', 'Index', 'Table name', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(43, 'technical', 'objective', 'Which SQL clause is used to filter records?', 'WHERE', 'HAVING', 'FILTER', 'CONDITION', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(44, 'technical', 'objective', 'What does JOIN do in SQL?', 'Combines rows from two tables', 'Deletes rows', 'Updates rows', 'Creates new table', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(45, 'technical', 'objective', 'Which data structure uses LIFO (Last In First Out)?', 'Queue', 'Stack', 'Array', 'Linked List', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:46'),
(46, 'technical', 'objective', 'What is a primary key constraint?', 'Ensures unique values', 'Allows null values', 'Links two tables', 'Speeds up queries', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(47, 'technical', 'objective', 'Which algorithm is used for sorting?', 'Bubble Sort', 'Quick Sort', 'Merge Sort', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(48, 'technical', 'objective', 'What is the time complexity of binary search?', 'O(n)', 'O(log n)', 'O(n²)', 'O(1)', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(49, 'technical', 'objective', 'What does RAM stand for?', 'Random Access Memory', 'Read Access Memory', 'Runtime Access Memory', 'Rapid Access Memory', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(50, 'technical', 'objective', 'Which company developed the Windows operating system?', 'Apple', 'Microsoft', 'Google', 'IBM', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(51, 'technical', 'objective', 'What does CPU stand for?', 'Central Processing Unit', 'Computer Processing Unit', 'Central Program Unit', 'Central Processor Unit', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(52, 'technical', 'objective', 'What is the cloud?', 'Internet-based computing', 'Local storage', 'Physical server', 'Network cable', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(53, 'technical', 'objective', 'Which of the following is a cyber attack?', 'Phishing', 'DDoS', 'Man-in-the-Middle', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(54, 'technical', 'objective', 'What is a firewall?', 'Network security system', 'Web browser', 'Database', 'Programming language', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(55, 'technical', 'objective', 'What does VPN stand for?', 'Virtual Private Network', 'Visual Private Network', 'Virtual Public Network', 'Virtual Protected Network', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(56, 'technical', 'objective', 'Which of the following is a social media platform?', 'Facebook', 'Twitter', 'Instagram', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(57, 'technical', 'objective', 'What does IoT stand for?', 'Internet of Things', 'Internet of Technology', 'Integration of Things', 'Internet on Things', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(58, 'technical', 'objective', 'Which company is known for search engine?', 'Google', 'Bing', 'Yahoo', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(59, 'technical', 'objective', 'What is AI?', 'Artificial Intelligence', 'Automated Intelligence', 'Advanced Intelligence', 'Applied Intelligence', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(60, 'technical', 'objective', 'What does ML stand for?', 'Machine Learning', 'Markup Language', 'Meta Language', 'Memory Learning', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(61, 'technical', 'objective', 'Which programming language is best for data science?', 'Python', 'R', 'SQL', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(62, 'softskills', 'objective', 'You are working on a team project and a teammate is struggling with their task. What do you do?', 'Ignore them', 'Do their work for them', 'Offer help and guidance', 'Complain to the manager', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(63, 'softskills', 'objective', 'How do you handle a tight deadline?', 'Panic', 'Work overtime alone', 'Prioritize tasks and communicate with team', 'Give up', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(64, 'softskills', 'objective', 'A customer reports a bug right before product release. What is your response?', 'Ignore the bug', 'Blame the customer', 'Acknowledge and schedule fix', 'Pretend not to hear', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:47'),
(65, 'softskills', 'objective', 'How do you respond to constructive criticism?', 'Get defensive', 'Ignore it', 'Listen and improve', 'Argue back', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(66, 'softskills', 'objective', 'Your manager assigns you a task you\'ve never done before. What do you do?', 'Refuse to do it', 'Attempt without asking', 'Ask for guidance and learn', 'Complain to colleagues', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(67, 'softskills', 'objective', 'A colleague takes credit for your work. How do you handle it?', 'Stay silent', 'Confront aggressively', 'Speak privately with your manager', 'Do the same to them', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(68, 'softskills', 'objective', 'How do you prioritize multiple tasks?', 'Work on easiest first', 'Work on hardest first', 'Based on urgency and importance', 'Randomly choose', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(69, 'softskills', 'objective', 'You make a mistake that affects the project. What do you do?', 'Hide it', 'Blame someone else', 'Take responsibility and fix it', 'Wait for someone to notice', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(70, 'softskills', 'objective', 'How do you handle conflict with a coworker?', 'Avoid them', 'Argue publicly', 'Discuss privately and find solution', 'Complain to HR immediately', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(71, 'softskills', 'objective', 'What is the best way to communicate technical issues to non-technical people?', 'Use technical jargon', 'Use simple analogies', 'Send a long email', 'Avoid explaining', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(72, 'softskills', 'objective', 'You notice a security vulnerability in the code. What do you do?', 'Ignore it', 'Fix it silently', 'Report it to the team lead', 'Exploit it', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(73, 'softskills', 'objective', 'How do you handle stress at work?', 'Take frequent breaks', 'Practice deep breathing', 'Prioritize tasks', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(74, 'softskills', 'objective', 'What is the most important quality in a team member?', 'Reliability', 'Speed', 'Experience', 'Education', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(75, 'softskills', 'objective', 'How do you handle a situation where you disagree with the project approach?', 'Stay silent', 'Argue aggressively', 'Present alternative solution professionally', 'Quit the project', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:48'),
(76, 'softskills', 'objective', 'What is active listening?', 'Hearing words', 'Waiting to speak', 'Understanding and responding thoughtfully', 'Taking notes', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(77, 'softskills', 'objective', 'How do you build trust with your team?', 'Complete tasks on time', 'Be honest and transparent', 'Help others', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(78, 'softskills', 'objective', 'What would you do if you are overworked?', 'Burn out silently', 'Request help or reprioritization', 'Work 24/7', 'Quit immediately', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(79, 'softskills', 'objective', 'How do you celebrate team success?', 'Take all credit', 'Share recognition with team', 'Ignore it', 'Take a day off', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(80, 'softskills', 'objective', 'What is the best way to give feedback to a peer?', 'Publicly criticize', 'Send an angry email', 'Private, constructive conversation', 'Ignore the issue', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(81, 'softskills', 'objective', 'How do you adapt to a new technology or process?', 'Resist change', 'Learn quickly and ask questions', 'Wait for training', 'Avoid using it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(82, 'softskills', 'objective', 'What does empathy mean in the workplace?', 'Understanding others\' feelings', 'Being sympathetic', 'Ignoring emotions', 'Staying professional', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(83, 'softskills', 'objective', 'How do you handle an angry customer?', 'Argue back', 'Listen and apologize', 'Hang up', 'Blame the company', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(84, 'softskills', 'objective', 'What is the best way to manage your time?', 'Make a to-do list', 'Prioritize tasks', 'Avoid multitasking', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(85, 'softskills', 'objective', 'How do you maintain work-life balance?', 'Work 24/7', 'Set boundaries and take breaks', 'Never take vacation', 'Check emails all night', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(86, 'softskills', 'objective', 'What would you do if you see a colleague being bullied?', 'Ignore it', 'Join the bully', 'Report to manager', 'Laugh at them', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(87, 'softskills', 'objective', 'How do you handle a situation where you don\'t know the answer?', 'Lie', 'Say \'I don\'t know, but I\'ll find out\'', 'Guess', 'Avoid the question', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(88, 'softskills', 'objective', 'What is the most effective leadership style?', 'Authoritarian', 'Democratic', 'Laissez-faire', 'Situational', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(89, 'softskills', 'objective', 'How do you motivate a demotivated team member?', 'Criticize them', 'Recognize their efforts and encourage', 'Ignore them', 'Replace them', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(90, 'softskills', 'objective', 'What is the key to successful negotiation?', 'Winning at all costs', 'Finding win-win solution', 'Walking away', 'Aggressive demands', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(91, 'softskills', 'objective', 'How do you handle gossip in the workplace?', 'Participate', 'Spread more gossip', 'Avoid and redirect conversation', 'Report immediately', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(92, 'softskills', 'objective', 'What is the most important soft skill for a manager?', 'Technical knowledge', 'Communication', 'Speed', 'Experience', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(93, 'softskills', 'objective', 'How do you respond to a missed deadline?', 'Make excuses', 'Apologize and provide solution', 'Blame others', 'Ignore it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(94, 'softskills', 'objective', 'What is the best way to ask for help?', 'Never ask', 'Ask clearly and respectfully', 'Demand help', 'Wait for offer', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(95, 'softskills', 'objective', 'How do you handle a situation where you are interrupted constantly?', 'Stay silent', 'Politely ask to finish', 'Yell at them', 'Walk away', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(96, 'softskills', 'objective', 'What is the most effective way to learn new skills?', 'Only formal training', 'Self-study and practice', 'Wait for promotion', 'Avoid learning', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:49'),
(97, 'softskills', 'objective', 'How do you respond to a rejected idea?', 'Give up', 'Get angry', 'Ask for feedback and improve', 'Blame others', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(98, 'softskills', 'objective', 'What is the best way to build professional relationships?', 'Network genuinely', 'Use people for gain', 'Avoid socializing', 'Stay isolated', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(99, 'softskills', 'objective', 'How do you handle a difficult decision?', 'Procrastinate', 'Analyze options and decide', 'Ask everyone', 'Flip a coin', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(100, 'softskills', 'objective', 'What is the most important factor for career growth?', 'Hard work only', 'Luck', 'Continuous learning and adaptability', 'Degrees', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(101, 'softskills', 'objective', 'How do you handle a situation where you are wrongly accused?', 'Stay silent', 'Yell at accuser', 'Present facts calmly', 'Quit', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(102, 'softskills', 'objective', 'What is the best way to show leadership?', 'Give orders', 'Lead by example', 'Take credit', 'Avoid responsibility', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(103, 'softskills', 'objective', 'How do you handle a team member who is not contributing?', 'Do their work', 'Confront privately and offer support', 'Ignore them', 'Report immediately', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(104, 'softskills', 'objective', 'What is the most effective way to resolve a dispute?', 'Escalate immediately', 'Mediate and find common ground', 'Take sides', 'Avoid', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(105, 'softskills', 'objective', 'How do you maintain positivity during challenges?', 'Focus on solutions', 'Complain', 'Give up', 'Blame circumstances', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(106, 'softskills', 'objective', 'What is the best way to impress your manager?', 'Take initiative and deliver results', 'Flatter them', 'Work late always', 'Gossip', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(107, 'softskills', 'objective', 'How do you handle a toxic coworker?', 'Engage in drama', 'Maintain professionalism and distance', 'Complain constantly', 'Fight back', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(108, 'softskills', 'objective', 'What is the most important quality for career success?', 'Technical skills only', 'Soft skills only', 'Combination of both', 'Luck', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(109, 'softskills', 'objective', 'How do you approach a performance review?', 'Get defensive', 'Be open to feedback and discuss growth', 'Argue every point', 'Skip it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(110, 'softskills', 'objective', 'What is the best way to build confidence?', 'Compare to others', 'Celebrate small wins', 'Avoid challenges', 'Wait for perfection', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(111, 'softskills', 'objective', 'How do you handle success?', 'Stay humble and grateful', 'Brag about it', 'Ignore it', 'Take all credit', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(112, 'softskills', 'objective', 'What is the most important lesson for a professional?', 'Always be right', 'Continuous improvement', 'Work harder than everyone', 'Follow orders', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(113, 'technical', 'objective', 'What does HTML stand for?', 'Hyper Text Markup Language', 'Home Tool Markup Language', 'High Text Machine Language', 'Hyper Transfer Markup Language', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(114, 'technical', 'objective', 'Which CSS property is used to change the text color of an element?', 'text-color', 'color', 'font-color', 'bgcolor', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(115, 'technical', 'objective', 'What does PHP stand for?', 'Personal Home Page', 'Preprocessor', 'Hypertext Preprocessor', 'Programming Language', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:50'),
(116, 'technical', 'objective', 'Which SQL statement is used to extract data from a database?', 'SELECT', 'EXTRACT', 'GET', 'OPEN', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(117, 'technical', 'objective', 'What does JavaScript use to declare a variable?', 'var', 'let', 'const', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(118, 'technical', 'objective', 'Which of the following is a valid way to comment in Python?', '// comment', '# comment', '/* comment */', '<!-- comment -->', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(119, 'technical', 'objective', 'What does CSS stand for?', 'Creative Style Sheets', 'Computer Style Sheets', 'Cascading Style Sheets', 'Colorful Style Sheets', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(120, 'technical', 'objective', 'Which tag is used to create a hyperlink in HTML?', '<link>', '<a>', '<href>', '<url>', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(121, 'technical', 'objective', 'What does SQL stand for?', 'Structured Question Language', 'Structured Query Language', 'Simple Query Language', 'Standard Query Language', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(122, 'technical', 'objective', 'Which operator is used for \'not equal to\' in PHP?', '!=', '<>', '!==', 'Both A and B', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(123, 'technical', 'objective', 'What is the correct way to start a session in PHP?', 'session_start()', 'start_session()', 'session_begin()', 'begin_session()', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(124, 'technical', 'objective', 'Which function is used to include a file in PHP?', 'include()', 'require()', 'Both A and B', 'import()', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(125, 'technical', 'objective', 'What does AJAX stand for?', 'Asynchronous JavaScript and XML', 'Advanced JavaScript and XML', 'Asynchronous JSON and XML', 'Active JavaScript and XML', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(126, 'technical', 'objective', 'Which method is used to send data to the server without reloading the page?', 'GET', 'POST', 'AJAX', 'FETCH', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(127, 'technical', 'objective', 'What does API stand for?', 'Application Programming Interface', 'Application Program Interface', 'Advanced Programming Interface', 'Application Protocol Interface', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(128, 'technical', 'objective', 'Which of the following is a NoSQL database?', 'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(129, 'technical', 'objective', 'What is the default port for MySQL?', '3306', '5432', '8080', '80', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(130, 'technical', 'objective', 'Which Git command is used to create a new branch?', 'git branch new-branch', 'git checkout -b new-branch', 'git create branch', 'Both A and B', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(131, 'technical', 'objective', 'What does OOP stand for?', 'Object-Oriented Programming', 'Ordered Object Programming', 'Object-Oriented Protocol', 'Object Organization Programming', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(132, 'technical', 'objective', 'Which principle is NOT part of OOP?', 'Inheritance', 'Polymorphism', 'Encapsulation', 'Compilation', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(133, 'technical', 'objective', 'What is the output of 5 + \'5\' in JavaScript?', '10', '55', 'Error', 'Undefined', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:51'),
(134, 'technical', 'objective', 'Which HTML tag is used to display an image?', '<img>', '<image>', '<src>', '<pic>', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(135, 'technical', 'objective', 'What does JSON stand for?', 'JavaScript Object Notation', 'Java Object Notation', 'JavaScript Oriented Notation', 'Java Script Object Notation', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(136, 'technical', 'objective', 'Which HTTP method is used to retrieve data from a server?', 'POST', 'GET', 'PUT', 'DELETE', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(137, 'technical', 'objective', 'What does CRUD stand for?', 'Create, Read, Update, Delete', 'Create, Retrieve, Update, Delete', 'Create, Read, Upload, Delete', 'Create, Read, Update, Drop', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(138, 'technical', 'objective', 'Which of the following is a programming paradigm?', 'Procedural', 'Object-Oriented', 'Functional', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(139, 'technical', 'objective', 'What does IDE stand for?', 'Integrated Development Environment', 'Integrated Design Environment', 'Internal Development Environment', 'Integrated Data Environment', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(140, 'technical', 'objective', 'Which of the following is a version control system?', 'Git', 'SVN', 'Mercurial', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(141, 'technical', 'objective', 'What does DNS stand for?', 'Domain Name System', 'Data Name System', 'Domain Network System', 'Digital Name System', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(142, 'technical', 'objective', 'Which protocol is used for secure communication over the internet?', 'HTTP', 'FTP', 'HTTPS', 'SMTP', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(143, 'technical', 'objective', 'What is the primary key in a database?', 'Unique identifier for a record', 'Foreign key reference', 'Index', 'Table name', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(144, 'technical', 'objective', 'Which SQL clause is used to filter records?', 'WHERE', 'HAVING', 'FILTER', 'CONDITION', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(145, 'technical', 'objective', 'What does JOIN do in SQL?', 'Combines rows from two tables', 'Deletes rows', 'Updates rows', 'Creates new table', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(146, 'technical', 'objective', 'Which data structure uses LIFO (Last In First Out)?', 'Queue', 'Stack', 'Array', 'Linked List', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(147, 'technical', 'objective', 'What is a primary key constraint?', 'Ensures unique values', 'Allows null values', 'Links two tables', 'Speeds up queries', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(148, 'technical', 'objective', 'Which algorithm is used for sorting?', 'Bubble Sort', 'Quick Sort', 'Merge Sort', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(149, 'technical', 'objective', 'What is the time complexity of binary search?', 'O(n)', 'O(log n)', 'O(n²)', 'O(1)', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(150, 'technical', 'objective', 'What does RAM stand for?', 'Random Access Memory', 'Read Access Memory', 'Runtime Access Memory', 'Rapid Access Memory', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(151, 'technical', 'objective', 'Which company developed the Windows operating system?', 'Apple', 'Microsoft', 'Google', 'IBM', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:52'),
(152, 'technical', 'objective', 'What does CPU stand for?', 'Central Processing Unit', 'Computer Processing Unit', 'Central Program Unit', 'Central Processor Unit', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(153, 'technical', 'objective', 'What is the cloud?', 'Internet-based computing', 'Local storage', 'Physical server', 'Network cable', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(154, 'technical', 'objective', 'Which of the following is a cyber attack?', 'Phishing', 'DDoS', 'Man-in-the-Middle', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(155, 'technical', 'objective', 'What is a firewall?', 'Network security system', 'Web browser', 'Database', 'Programming language', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(156, 'technical', 'objective', 'What does VPN stand for?', 'Virtual Private Network', 'Visual Private Network', 'Virtual Public Network', 'Virtual Protected Network', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(157, 'technical', 'objective', 'Which of the following is a social media platform?', 'Facebook', 'Twitter', 'Instagram', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(158, 'technical', 'objective', 'What does IoT stand for?', 'Internet of Things', 'Internet of Technology', 'Integration of Things', 'Internet on Things', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(159, 'technical', 'objective', 'Which company is known for search engine?', 'Google', 'Bing', 'Yahoo', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(160, 'technical', 'objective', 'What is AI?', 'Artificial Intelligence', 'Automated Intelligence', 'Advanced Intelligence', 'Applied Intelligence', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(161, 'technical', 'objective', 'What does ML stand for?', 'Machine Learning', 'Markup Language', 'Meta Language', 'Memory Learning', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(162, 'technical', 'objective', 'Which programming language is best for data science?', 'Python', 'R', 'SQL', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(163, 'softskills', 'objective', 'You are working on a team project and a teammate is struggling with their task. What do you do?', 'Ignore them', 'Do their work for them', 'Offer help and guidance', 'Complain to the manager', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(164, 'softskills', 'objective', 'How do you handle a tight deadline?', 'Panic', 'Work overtime alone', 'Prioritize tasks and communicate with team', 'Give up', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(165, 'softskills', 'objective', 'A customer reports a bug right before product release. What is your response?', 'Ignore the bug', 'Blame the customer', 'Acknowledge and schedule fix', 'Pretend not to hear', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(166, 'softskills', 'objective', 'How do you respond to constructive criticism?', 'Get defensive', 'Ignore it', 'Listen and improve', 'Argue back', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(167, 'softskills', 'objective', 'Your manager assigns you a task you\'ve never done before. What do you do?', 'Refuse to do it', 'Attempt without asking', 'Ask for guidance and learn', 'Complain to colleagues', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:53'),
(168, 'softskills', 'objective', 'A colleague takes credit for your work. How do you handle it?', 'Stay silent', 'Confront aggressively', 'Speak privately with your manager', 'Do the same to them', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(169, 'softskills', 'objective', 'How do you prioritize multiple tasks?', 'Work on easiest first', 'Work on hardest first', 'Based on urgency and importance', 'Randomly choose', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(170, 'softskills', 'objective', 'You make a mistake that affects the project. What do you do?', 'Hide it', 'Blame someone else', 'Take responsibility and fix it', 'Wait for someone to notice', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(171, 'softskills', 'objective', 'How do you handle conflict with a coworker?', 'Avoid them', 'Argue publicly', 'Discuss privately and find solution', 'Complain to HR immediately', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(172, 'softskills', 'objective', 'What is the best way to communicate technical issues to non-technical people?', 'Use technical jargon', 'Use simple analogies', 'Send a long email', 'Avoid explaining', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(173, 'softskills', 'objective', 'You notice a security vulnerability in the code. What do you do?', 'Ignore it', 'Fix it silently', 'Report it to the team lead', 'Exploit it', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(174, 'softskills', 'objective', 'How do you handle stress at work?', 'Take frequent breaks', 'Practice deep breathing', 'Prioritize tasks', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(175, 'softskills', 'objective', 'What is the most important quality in a team member?', 'Reliability', 'Speed', 'Experience', 'Education', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(176, 'softskills', 'objective', 'How do you handle a situation where you disagree with the project approach?', 'Stay silent', 'Argue aggressively', 'Present alternative solution professionally', 'Quit the project', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(177, 'softskills', 'objective', 'What is active listening?', 'Hearing words', 'Waiting to speak', 'Understanding and responding thoughtfully', 'Taking notes', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(178, 'softskills', 'objective', 'How do you build trust with your team?', 'Complete tasks on time', 'Be honest and transparent', 'Help others', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(179, 'softskills', 'objective', 'What would you do if you are overworked?', 'Burn out silently', 'Request help or reprioritization', 'Work 24/7', 'Quit immediately', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(180, 'softskills', 'objective', 'How do you celebrate team success?', 'Take all credit', 'Share recognition with team', 'Ignore it', 'Take a day off', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(181, 'softskills', 'objective', 'What is the best way to give feedback to a peer?', 'Publicly criticize', 'Send an angry email', 'Private, constructive conversation', 'Ignore the issue', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(182, 'softskills', 'objective', 'How do you adapt to a new technology or process?', 'Resist change', 'Learn quickly and ask questions', 'Wait for training', 'Avoid using it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(183, 'softskills', 'objective', 'What does empathy mean in the workplace?', 'Understanding others\' feelings', 'Being sympathetic', 'Ignoring emotions', 'Staying professional', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(184, 'softskills', 'objective', 'How do you handle an angry customer?', 'Argue back', 'Listen and apologize', 'Hang up', 'Blame the company', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(185, 'softskills', 'objective', 'What is the best way to manage your time?', 'Make a to-do list', 'Prioritize tasks', 'Avoid multitasking', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(186, 'softskills', 'objective', 'How do you maintain work-life balance?', 'Work 24/7', 'Set boundaries and take breaks', 'Never take vacation', 'Check emails all night', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(187, 'softskills', 'objective', 'What would you do if you see a colleague being bullied?', 'Ignore it', 'Join the bully', 'Report to manager', 'Laugh at them', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(188, 'softskills', 'objective', 'How do you handle a situation where you don\'t know the answer?', 'Lie', 'Say \'I don\'t know, but I\'ll find out\'', 'Guess', 'Avoid the question', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:54'),
(189, 'softskills', 'objective', 'What is the most effective leadership style?', 'Authoritarian', 'Democratic', 'Laissez-faire', 'Situational', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(190, 'softskills', 'objective', 'How do you motivate a demotivated team member?', 'Criticize them', 'Recognize their efforts and encourage', 'Ignore them', 'Replace them', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(191, 'softskills', 'objective', 'What is the key to successful negotiation?', 'Winning at all costs', 'Finding win-win solution', 'Walking away', 'Aggressive demands', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(192, 'softskills', 'objective', 'How do you handle gossip in the workplace?', 'Participate', 'Spread more gossip', 'Avoid and redirect conversation', 'Report immediately', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(193, 'softskills', 'objective', 'What is the most important soft skill for a manager?', 'Technical knowledge', 'Communication', 'Speed', 'Experience', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(194, 'softskills', 'objective', 'How do you respond to a missed deadline?', 'Make excuses', 'Apologize and provide solution', 'Blame others', 'Ignore it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(195, 'softskills', 'objective', 'What is the best way to ask for help?', 'Never ask', 'Ask clearly and respectfully', 'Demand help', 'Wait for offer', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(196, 'softskills', 'objective', 'How do you handle a situation where you are interrupted constantly?', 'Stay silent', 'Politely ask to finish', 'Yell at them', 'Walk away', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(197, 'softskills', 'objective', 'What is the most effective way to learn new skills?', 'Only formal training', 'Self-study and practice', 'Wait for promotion', 'Avoid learning', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(198, 'softskills', 'objective', 'How do you respond to a rejected idea?', 'Give up', 'Get angry', 'Ask for feedback and improve', 'Blame others', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:55'),
(199, 'softskills', 'objective', 'What is the best way to build professional relationships?', 'Network genuinely', 'Use people for gain', 'Avoid socializing', 'Stay isolated', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(200, 'softskills', 'objective', 'How do you handle a difficult decision?', 'Procrastinate', 'Analyze options and decide', 'Ask everyone', 'Flip a coin', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(201, 'softskills', 'objective', 'What is the most important factor for career growth?', 'Hard work only', 'Luck', 'Continuous learning and adaptability', 'Degrees', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(202, 'softskills', 'objective', 'How do you handle a situation where you are wrongly accused?', 'Stay silent', 'Yell at accuser', 'Present facts calmly', 'Quit', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(203, 'softskills', 'objective', 'What is the best way to show leadership?', 'Give orders', 'Lead by example', 'Take credit', 'Avoid responsibility', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(204, 'softskills', 'objective', 'How do you handle a team member who is not contributing?', 'Do their work', 'Confront privately and offer support', 'Ignore them', 'Report immediately', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(205, 'softskills', 'objective', 'What is the most effective way to resolve a dispute?', 'Escalate immediately', 'Mediate and find common ground', 'Take sides', 'Avoid', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(206, 'softskills', 'objective', 'How do you maintain positivity during challenges?', 'Focus on solutions', 'Complain', 'Give up', 'Blame circumstances', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(207, 'softskills', 'objective', 'What is the best way to impress your manager?', 'Take initiative and deliver results', 'Flatter them', 'Work late always', 'Gossip', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(208, 'softskills', 'objective', 'How do you handle a toxic coworker?', 'Engage in drama', 'Maintain professionalism and distance', 'Complain constantly', 'Fight back', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(209, 'softskills', 'objective', 'What is the most important quality for career success?', 'Technical skills only', 'Soft skills only', 'Combination of both', 'Luck', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(210, 'softskills', 'objective', 'How do you approach a performance review?', 'Get defensive', 'Be open to feedback and discuss growth', 'Argue every point', 'Skip it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(211, 'softskills', 'objective', 'What is the best way to build confidence?', 'Compare to others', 'Celebrate small wins', 'Avoid challenges', 'Wait for perfection', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(212, 'softskills', 'objective', 'How do you handle success?', 'Stay humble and grateful', 'Brag about it', 'Ignore it', 'Take all credit', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(213, 'softskills', 'objective', 'What is the most important lesson for a professional?', 'Always be right', 'Continuous improvement', 'Work harder than everyone', 'Follow orders', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 09:36:56'),
(214, 'technical', 'objective', 'What does HTML stand for?', 'Hyper Text Markup Language', 'Home Tool Markup Language', 'High Text Machine Language', 'Hyper Transfer Markup Language', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06'),
(215, 'technical', 'objective', 'Which CSS property is used to change the text color of an element?', 'text-color', 'color', 'font-color', 'bgcolor', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06'),
(216, 'technical', 'objective', 'What does PHP stand for?', 'Personal Home Page', 'Preprocessor', 'Hypertext Preprocessor', 'Programming Language', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06'),
(217, 'technical', 'objective', 'Which SQL statement is used to extract data from a database?', 'SELECT', 'EXTRACT', 'GET', 'OPEN', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06'),
(218, 'technical', 'objective', 'What does JavaScript use to declare a variable?', 'var', 'let', 'const', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06'),
(219, 'technical', 'objective', 'Which of the following is a valid way to comment in Python?', '// comment', '# comment', '/* comment */', '<!-- comment -->', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06');
INSERT INTO `questions` (`id`, `category`, `question_type`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `expected_answer`, `code_snippet`, `language`, `recommendation_type`, `recommendation_title`, `recommendation_link`, `created_at`) VALUES
(220, 'technical', 'objective', 'What does CSS stand for?', 'Creative Style Sheets', 'Computer Style Sheets', 'Cascading Style Sheets', 'Colorful Style Sheets', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:06'),
(221, 'technical', 'objective', 'Which tag is used to create a hyperlink in HTML?', '<link>', '<a>', '<href>', '<url>', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(222, 'technical', 'objective', 'What does SQL stand for?', 'Structured Question Language', 'Structured Query Language', 'Simple Query Language', 'Standard Query Language', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(223, 'technical', 'objective', 'Which operator is used for \'not equal to\' in PHP?', '!=', '<>', '!==', 'Both A and B', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(224, 'technical', 'objective', 'What is the correct way to start a session in PHP?', 'session_start()', 'start_session()', 'session_begin()', 'begin_session()', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(225, 'technical', 'objective', 'Which function is used to include a file in PHP?', 'include()', 'require()', 'Both A and B', 'import()', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(226, 'technical', 'objective', 'What does AJAX stand for?', 'Asynchronous JavaScript and XML', 'Advanced JavaScript and XML', 'Asynchronous JSON and XML', 'Active JavaScript and XML', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(227, 'technical', 'objective', 'Which method is used to send data to the server without reloading the page?', 'GET', 'POST', 'AJAX', 'FETCH', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(228, 'technical', 'objective', 'What does API stand for?', 'Application Programming Interface', 'Application Program Interface', 'Advanced Programming Interface', 'Application Protocol Interface', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:07'),
(229, 'technical', 'objective', 'Which of the following is a NoSQL database?', 'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(230, 'technical', 'objective', 'What is the default port for MySQL?', '3306', '5432', '8080', '80', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(231, 'technical', 'objective', 'Which Git command is used to create a new branch?', 'git branch new-branch', 'git checkout -b new-branch', 'git create branch', 'Both A and B', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(232, 'technical', 'objective', 'What does OOP stand for?', 'Object-Oriented Programming', 'Ordered Object Programming', 'Object-Oriented Protocol', 'Object Organization Programming', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(233, 'technical', 'objective', 'Which principle is NOT part of OOP?', 'Inheritance', 'Polymorphism', 'Encapsulation', 'Compilation', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(234, 'technical', 'objective', 'What is the output of 5 + \'5\' in JavaScript?', '10', '55', 'Error', 'Undefined', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(235, 'technical', 'objective', 'Which HTML tag is used to display an image?', '<img>', '<image>', '<src>', '<pic>', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(236, 'technical', 'objective', 'What does JSON stand for?', 'JavaScript Object Notation', 'Java Object Notation', 'JavaScript Oriented Notation', 'Java Script Object Notation', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(237, 'technical', 'objective', 'Which HTTP method is used to retrieve data from a server?', 'POST', 'GET', 'PUT', 'DELETE', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(238, 'technical', 'objective', 'What does CRUD stand for?', 'Create, Read, Update, Delete', 'Create, Retrieve, Update, Delete', 'Create, Read, Upload, Delete', 'Create, Read, Update, Drop', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(239, 'technical', 'objective', 'Which of the following is a programming paradigm?', 'Procedural', 'Object-Oriented', 'Functional', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(240, 'technical', 'objective', 'What does IDE stand for?', 'Integrated Development Environment', 'Integrated Design Environment', 'Internal Development Environment', 'Integrated Data Environment', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:08'),
(241, 'technical', 'objective', 'Which of the following is a version control system?', 'Git', 'SVN', 'Mercurial', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(242, 'technical', 'objective', 'What does DNS stand for?', 'Domain Name System', 'Data Name System', 'Domain Network System', 'Digital Name System', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(243, 'technical', 'objective', 'Which protocol is used for secure communication over the internet?', 'HTTP', 'FTP', 'HTTPS', 'SMTP', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(244, 'technical', 'objective', 'What is the primary key in a database?', 'Unique identifier for a record', 'Foreign key reference', 'Index', 'Table name', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(245, 'technical', 'objective', 'Which SQL clause is used to filter records?', 'WHERE', 'HAVING', 'FILTER', 'CONDITION', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(246, 'technical', 'objective', 'What does JOIN do in SQL?', 'Combines rows from two tables', 'Deletes rows', 'Updates rows', 'Creates new table', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(247, 'technical', 'objective', 'Which data structure uses LIFO (Last In First Out)?', 'Queue', 'Stack', 'Array', 'Linked List', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(248, 'technical', 'objective', 'What is a primary key constraint?', 'Ensures unique values', 'Allows null values', 'Links two tables', 'Speeds up queries', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(249, 'technical', 'objective', 'Which algorithm is used for sorting?', 'Bubble Sort', 'Quick Sort', 'Merge Sort', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(250, 'technical', 'objective', 'What is the time complexity of binary search?', 'O(n)', 'O(log n)', 'O(n²)', 'O(1)', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(251, 'technical', 'objective', 'What does RAM stand for?', 'Random Access Memory', 'Read Access Memory', 'Runtime Access Memory', 'Rapid Access Memory', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(252, 'technical', 'objective', 'Which company developed the Windows operating system?', 'Apple', 'Microsoft', 'Google', 'IBM', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(253, 'technical', 'objective', 'What does CPU stand for?', 'Central Processing Unit', 'Computer Processing Unit', 'Central Program Unit', 'Central Processor Unit', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:09'),
(254, 'technical', 'objective', 'What is the cloud?', 'Internet-based computing', 'Local storage', 'Physical server', 'Network cable', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(255, 'technical', 'objective', 'Which of the following is a cyber attack?', 'Phishing', 'DDoS', 'Man-in-the-Middle', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(256, 'technical', 'objective', 'What is a firewall?', 'Network security system', 'Web browser', 'Database', 'Programming language', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(257, 'technical', 'objective', 'What does VPN stand for?', 'Virtual Private Network', 'Visual Private Network', 'Virtual Public Network', 'Virtual Protected Network', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(258, 'technical', 'objective', 'Which of the following is a social media platform?', 'Facebook', 'Twitter', 'Instagram', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(259, 'technical', 'objective', 'What does IoT stand for?', 'Internet of Things', 'Internet of Technology', 'Integration of Things', 'Internet on Things', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(260, 'technical', 'objective', 'Which company is known for search engine?', 'Google', 'Bing', 'Yahoo', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(261, 'technical', 'objective', 'What is AI?', 'Artificial Intelligence', 'Automated Intelligence', 'Advanced Intelligence', 'Applied Intelligence', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(262, 'technical', 'objective', 'What does ML stand for?', 'Machine Learning', 'Markup Language', 'Meta Language', 'Memory Learning', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(263, 'technical', 'objective', 'Which programming language is best for data science?', 'Python', 'R', 'SQL', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(264, 'technical', 'objective', 'What is the difference between GET and POST in HTTP?', 'GET is faster, POST is slower', 'GET has body, POST has URL params', 'GET appends data to URL, POST sends in request body', 'GET is for forms, POST is for images', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(265, 'technical', 'objective', 'What is a closure in JavaScript?', 'A closed function', 'A function with access to outer function\'s scope', 'A private variable', 'A built-in object', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:10'),
(266, 'technical', 'objective', 'What is the purpose of the \'use strict\' directive in JavaScript?', 'Enables strict parsing of JSON', 'Enables modern JavaScript features', 'Enforces stricter parsing and error handling', 'Disables deprecated features', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(267, 'technical', 'objective', 'Explain the concept of hoisting in JavaScript', 'Moving declarations to the bottom', 'Moving declarations to the top', 'Variable initialization only', 'Function expression hoisting', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(268, 'technical', 'objective', 'What is the event loop in JavaScript?', 'A loop that runs events', 'Handles asynchronous callbacks', 'An infinite loop', 'A timer function', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(269, 'technical', 'objective', 'What is the difference between == and === in JavaScript?', 'No difference', '== compares value, === compares value and type', '== compares type, === compares value', '== is for numbers only', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(270, 'technical', 'objective', 'What is a promise in JavaScript?', 'A guarantee to return data', 'An object representing eventual completion of async operation', 'A callback function', 'A synchronous operation', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(271, 'technical', 'objective', 'What is the purpose of the \'this\' keyword?', 'Refers to current object', 'Refers to global object', 'Refers to parent function', 'All of the above', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(272, 'technical', 'objective', 'Explain the difference between let, const, and var', 'No difference', 'var is function-scoped, let/const are block-scoped', 'let cannot be reassigned', 'const can be reassigned', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(273, 'technical', 'objective', 'What is REST API?', 'Random API', 'Representational State Transfer API', 'Reliable State Transfer API', 'Rapid Execution API', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(274, 'technical', 'objective', 'What is normalization in databases?', 'Deleting duplicates', 'Organizing data to reduce redundancy', 'Adding more tables', 'Compressing data', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(275, 'technical', 'objective', 'What is a trigger in SQL?', 'Automatically executes on table events', 'A manual query', 'A scheduled job', 'An index', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(276, 'technical', 'objective', 'What is the difference between clustered and non-clustered index?', 'Clustered sorts data, non-clustered doesn\'t', 'No difference', 'Clustered is faster', 'Non-clustered is default', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:11'),
(277, 'technical', 'objective', 'What is a deadlock in databases?', 'Two processes waiting for each other', 'A crashed query', 'A locked table', 'A missing index', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(278, 'technical', 'objective', 'What is a view in SQL?', 'A virtual table based on query result', 'An image', 'A backup', 'A stored procedure', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(279, 'technical', 'objective', 'What is the difference between git merge and git rebase?', 'No difference', 'Merge creates commit, rebase rewrites history', 'Rebase is faster', 'Merge is safer', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(280, 'technical', 'objective', 'What is the difference between monolithic and microservices?', 'Monolith is one app, microservices are separate', 'No difference', 'Microservices are slower', 'Monolith is newer', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(281, 'technical', 'objective', 'What is the difference between unit test and integration test?', 'No difference', 'Unit tests single component, integration tests multiple', 'Integration tests are faster', 'Unit tests need database', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(282, 'technical', 'objective', 'What is the difference between white box and black box testing?', 'White box tests internals, black box tests functionality', 'No difference', 'White box needs user', 'Black box needs code', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(283, 'technical', 'objective', 'Give an example of a creational design pattern', 'Singleton', 'Observer', 'Facade', 'Strategy', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(284, 'technical', 'objective', 'What is the Factory pattern?', 'Creates objects without specifying class', 'Creates databases', 'Creates tables', 'Creates indexes', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(285, 'technical', 'objective', 'What is the difference between IPv4 and IPv6?', 'IPv4 32-bit, IPv6 128-bit', 'IPv6 is older', 'No difference', 'IPv4 has more addresses', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(286, 'technical', 'objective', 'What is a DNS server?', 'Converts domain names to IP addresses', 'Stores websites', 'Manages emails', 'Hosts databases', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(287, 'technical', 'objective', 'What is authentication vs authorization?', 'AuthN is identity, AuthZ is permissions', 'Both are same', 'AuthZ is identity', 'AuthN is permissions', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(288, 'softskills', 'objective', 'You are working on a team project and a teammate is struggling with their task. What do you do?', 'Ignore them', 'Do their work for them', 'Offer help and guidance', 'Complain to the manager', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:12'),
(289, 'softskills', 'objective', 'How do you handle a tight deadline?', 'Panic', 'Work overtime alone', 'Prioritize tasks and communicate with team', 'Give up', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(290, 'softskills', 'objective', 'A customer reports a bug right before product release. What is your response?', 'Ignore the bug', 'Blame the customer', 'Acknowledge and schedule fix', 'Pretend not to hear', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(291, 'softskills', 'objective', 'How do you respond to constructive criticism?', 'Get defensive', 'Ignore it', 'Listen and improve', 'Argue back', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(292, 'softskills', 'objective', 'Your manager assigns you a task you\'ve never done before. What do you do?', 'Refuse to do it', 'Attempt without asking', 'Ask for guidance and learn', 'Complain to colleagues', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(293, 'softskills', 'objective', 'A colleague takes credit for your work. How do you handle it?', 'Stay silent', 'Confront aggressively', 'Speak privately with your manager', 'Do the same to them', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(294, 'softskills', 'objective', 'How do you prioritize multiple tasks?', 'Work on easiest first', 'Work on hardest first', 'Based on urgency and importance', 'Randomly choose', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(295, 'softskills', 'objective', 'You make a mistake that affects the project. What do you do?', 'Hide it', 'Blame someone else', 'Take responsibility and fix it', 'Wait for someone to notice', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(296, 'softskills', 'objective', 'How do you handle conflict with a coworker?', 'Avoid them', 'Argue publicly', 'Discuss privately and find solution', 'Complain to HR immediately', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(297, 'softskills', 'objective', 'What is the best way to communicate technical issues to non-technical people?', 'Use technical jargon', 'Use simple analogies', 'Send a long email', 'Avoid explaining', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(298, 'softskills', 'objective', 'You notice a security vulnerability in the code. What do you do?', 'Ignore it', 'Fix it silently', 'Report it to the team lead', 'Exploit it', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(299, 'softskills', 'objective', 'How do you handle stress at work?', 'Take frequent breaks', 'Practice deep breathing', 'Prioritize tasks', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(300, 'softskills', 'objective', 'What is the most important quality in a team member?', 'Reliability', 'Speed', 'Experience', 'Education', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:13'),
(301, 'softskills', 'objective', 'How do you handle a situation where you disagree with the project approach?', 'Stay silent', 'Argue aggressively', 'Present alternative solution professionally', 'Quit the project', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(302, 'softskills', 'objective', 'What is active listening?', 'Hearing words', 'Waiting to speak', 'Understanding and responding thoughtfully', 'Taking notes', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(303, 'softskills', 'objective', 'How do you build trust with your team?', 'Complete tasks on time', 'Be honest and transparent', 'Help others', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(304, 'softskills', 'objective', 'What would you do if you are overworked?', 'Burn out silently', 'Request help or reprioritization', 'Work 24/7', 'Quit immediately', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(305, 'softskills', 'objective', 'How do you celebrate team success?', 'Take all credit', 'Share recognition with team', 'Ignore it', 'Take a day off', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(306, 'softskills', 'objective', 'What is the best way to give feedback to a peer?', 'Publicly criticize', 'Send an angry email', 'Private, constructive conversation', 'Ignore the issue', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(307, 'softskills', 'objective', 'How do you adapt to a new technology or process?', 'Resist change', 'Learn quickly and ask questions', 'Wait for training', 'Avoid using it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(308, 'softskills', 'objective', 'What does empathy mean in the workplace?', 'Understanding others\' feelings', 'Being sympathetic', 'Ignoring emotions', 'Staying professional', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(309, 'softskills', 'objective', 'How do you handle an angry customer?', 'Argue back', 'Listen and apologize', 'Hang up', 'Blame the company', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:14'),
(310, 'softskills', 'objective', 'What is the best way to manage your time?', 'Make a to-do list', 'Prioritize tasks', 'Avoid multitasking', 'All of the above', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(311, 'softskills', 'objective', 'How do you maintain work-life balance?', 'Work 24/7', 'Set boundaries and take breaks', 'Never take vacation', 'Check emails all night', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(312, 'softskills', 'objective', 'What would you do if you see a colleague being bullied?', 'Ignore it', 'Join the bully', 'Report to manager', 'Laugh at them', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(313, 'softskills', 'objective', 'How do you handle a situation where you don\'t know the answer?', 'Lie', 'Say \'I don\'t know, but I\'ll find out\'', 'Guess', 'Avoid the question', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(314, 'softskills', 'objective', 'What is the most effective leadership style?', 'Authoritarian', 'Democratic', 'Laissez-faire', 'Situational', 4, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(315, 'softskills', 'objective', 'How do you motivate a demotivated team member?', 'Criticize them', 'Recognize their efforts and encourage', 'Ignore them', 'Replace them', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(316, 'softskills', 'objective', 'What is the key to successful negotiation?', 'Winning at all costs', 'Finding win-win solution', 'Walking away', 'Aggressive demands', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(317, 'softskills', 'objective', 'How do you handle gossip in the workplace?', 'Participate', 'Spread more gossip', 'Avoid and redirect conversation', 'Report immediately', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(318, 'softskills', 'objective', 'What is the most important soft skill for a manager?', 'Technical knowledge', 'Communication', 'Speed', 'Experience', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:15'),
(319, 'softskills', 'objective', 'How do you respond to a missed deadline?', 'Make excuses', 'Apologize and provide solution', 'Blame others', 'Ignore it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(320, 'softskills', 'objective', 'What is the best way to ask for help?', 'Never ask', 'Ask clearly and respectfully', 'Demand help', 'Wait for offer', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(321, 'softskills', 'objective', 'How do you handle a situation where you are interrupted constantly?', 'Stay silent', 'Politely ask to finish', 'Yell at them', 'Walk away', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(322, 'softskills', 'objective', 'What is the most effective way to learn new skills?', 'Only formal training', 'Self-study and practice', 'Wait for promotion', 'Avoid learning', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(323, 'softskills', 'objective', 'How do you respond to a rejected idea?', 'Give up', 'Get angry', 'Ask for feedback and improve', 'Blame others', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(324, 'softskills', 'objective', 'What is the best way to build professional relationships?', 'Network genuinely', 'Use people for gain', 'Avoid socializing', 'Stay isolated', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(325, 'softskills', 'objective', 'How do you handle a difficult decision?', 'Procrastinate', 'Analyze options and decide', 'Ask everyone', 'Flip a coin', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(326, 'softskills', 'objective', 'What is the most important factor for career growth?', 'Hard work only', 'Luck', 'Continuous learning and adaptability', 'Degrees', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(327, 'softskills', 'objective', 'How do you handle a situation where you are wrongly accused?', 'Stay silent', 'Yell at accuser', 'Present facts calmly', 'Quit', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(328, 'softskills', 'objective', 'What is the best way to show leadership?', 'Give orders', 'Lead by example', 'Take credit', 'Avoid responsibility', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(329, 'softskills', 'objective', 'How do you handle a team member who is not contributing?', 'Do their work', 'Confront privately and offer support', 'Ignore them', 'Report immediately', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(330, 'softskills', 'objective', 'What is the most effective way to resolve a dispute?', 'Escalate immediately', 'Mediate and find common ground', 'Take sides', 'Avoid', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(331, 'softskills', 'objective', 'How do you maintain positivity during challenges?', 'Focus on solutions', 'Complain', 'Give up', 'Blame circumstances', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:16'),
(332, 'softskills', 'objective', 'What is the best way to impress your manager?', 'Take initiative and deliver results', 'Flatter them', 'Work late always', 'Gossip', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17'),
(333, 'softskills', 'objective', 'How do you handle a toxic coworker?', 'Engage in drama', 'Maintain professionalism and distance', 'Complain constantly', 'Fight back', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17'),
(334, 'softskills', 'objective', 'What is the most important quality for career success?', 'Technical skills only', 'Soft skills only', 'Combination of both', 'Luck', 3, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17'),
(335, 'softskills', 'objective', 'How do you approach a performance review?', 'Get defensive', 'Be open to feedback and discuss growth', 'Argue every point', 'Skip it', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17'),
(336, 'softskills', 'objective', 'What is the best way to build confidence?', 'Compare to others', 'Celebrate small wins', 'Avoid challenges', 'Wait for perfection', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17'),
(337, 'softskills', 'objective', 'How do you handle success?', 'Stay humble and grateful', 'Brag about it', 'Ignore it', 'Take all credit', 1, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17'),
(338, 'softskills', 'objective', 'What is the most important lesson for a professional?', 'Always be right', 'Continuous improvement', 'Work harder than everyone', 'Follow orders', 2, NULL, NULL, 'php', NULL, NULL, NULL, '2026-06-11 10:00:17');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` enum('technical','softskills') DEFAULT NULL,
  `score` int(11) DEFAULT 0,
  `total_questions` int(11) DEFAULT 0,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `status` enum('pass','fail') DEFAULT 'fail',
  `time_spent` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `category`, `score`, `total_questions`, `percentage`, `status`, `time_spent`, `created_at`) VALUES
(1, 1, 'technical', 1, 1, 100.00, 'fail', 0, '2026-06-11 07:01:59'),
(2, 1, 'technical', 4, 6, 66.67, 'fail', 0, '2026-06-11 07:54:40'),
(3, 1, 'technical', 19, 50, 38.00, 'fail', 0, '2026-06-11 10:15:16');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `category` enum('technical','soft') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_name`, `category`, `description`, `created_at`) VALUES
(1, 'Technical Assessment', 'technical', 'Comprehensive technical skills assessment covering programming, databases, and problem-solving', '2026-06-11 08:01:14'),
(2, 'Soft Skills Assessment', 'soft', 'Assessment of communication, teamwork, leadership, and adaptability skills', '2026-06-11 08:01:14'),
(3, 'Technical Assessment', 'technical', 'Comprehensive technical skills assessment', '2026-06-11 09:03:07'),
(4, 'Soft Skills Assessment', 'soft', 'Assessment of communication, teamwork, and leadership', '2026-06-11 09:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `student_results`
--

CREATE TABLE `student_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `date_taken` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `faculty` varchar(150) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `skills_interest` text DEFAULT NULL,
  `role` varchar(20) DEFAULT 'student',
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `student_id`, `password`, `login_attempts`, `locked_until`, `remember_token`, `created_at`, `phone`, `gender`, `dob`, `institution`, `faculty`, `department`, `level`, `skills_interest`, `role`, `profile_image`) VALUES
(1, 'Abba\'s Delight', 'delight@gmail.com', '26/26DL01', '$2y$10$5T6KW9KseRd11EXZ/JegguitP/33cMH8SDWHd4fLokLDGwbPiuE0.', 0, NULL, NULL, '2026-04-20 10:44:51', '08084093104', 'Female', '2026-06-23', 'University of Ilorin', 'Communication and Information Science', 'Computer Science', '400 Level', 'Web Development and Data Analysis', 'student', 'user_1_1781169241.png'),
(6, 'System Admin', 'admin@gmail.com', 'ADMIN001', '0192023a7bbd73250516f069df18b500', 0, NULL, NULL, '2026-05-09 11:15:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', NULL),
(7, 'Grace Oba', 'graceobanegha2@gmail.com', '22/01DL088', '$2y$10$Gs1RETv0Pz2oZOsa7QtL..zBlZG7fwMqh0zHwyzvpIDq826nFcyl.', 0, NULL, 'd8f444fd513c11eb2efee78341579004be2ee7ea63f3f3432556d0f49ac1ccf6', '2026-05-26 15:31:46', '09043902441', 'Female', '2000-06-23', 'University of Ilorin', 'Communication and Information Science', 'Computer Science', '400 Level', 'Web Development and Data Analysis', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `selected_option` int(11) DEFAULT NULL,
  `correct_option` int(11) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `assessment_category` varchar(50) DEFAULT 'technical'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_id`, `question_id`, `selected_option`, `correct_option`, `is_correct`, `assessment_category`) VALUES
(11, 1, 1, 0, 1, 0, 'technical'),
(12, 1, 2, 0, 3, 0, 'technical'),
(13, 1, 3, 0, 2, 0, 'technical'),
(14, 1, 4, 0, 3, 0, 'technical'),
(15, 7, 1, 1, 1, 1, 'technical'),
(16, 7, 2, 3, 3, 1, 'technical'),
(17, 7, 3, 0, 2, 0, 'technical'),
(18, 7, 4, 0, 3, 0, 'technical'),
(19, 1, 1, 1, 1, 1, 'technical'),
(20, 1, 2, 2, 2, 1, 'technical'),
(21, 1, 3, 2, 2, 1, 'technical'),
(22, 1, 4, 3, 3, 1, 'technical'),
(23, 1, 5, 2, 2, 1, 'technical'),
(24, 1, 6, 3, 2, 0, 'technical'),
(25, 1, 11, 0, 3, 0, 'technical'),
(26, 1, 2, 2, 2, 1, 'technical'),
(27, 1, 3, 0, 2, 0, 'technical'),
(28, 1, 4, 0, 3, 0, 'technical'),
(29, 1, 5, 0, 2, 0, 'technical'),
(30, 1, 6, 0, 2, 0, 'technical'),
(31, 1, 12, 0, 1, 0, 'technical'),
(32, 1, 13, 0, 2, 0, 'technical'),
(33, 1, 14, 3, 3, 1, 'technical'),
(34, 1, 15, 1, 1, 1, 'technical'),
(35, 1, 16, 0, 4, 0, 'technical'),
(36, 1, 17, 0, 2, 0, 'technical'),
(37, 1, 18, 0, 3, 0, 'technical'),
(38, 1, 19, 0, 2, 0, 'technical'),
(39, 1, 20, 0, 2, 0, 'technical'),
(40, 1, 21, 0, 4, 0, 'technical'),
(41, 1, 22, 0, 1, 0, 'technical'),
(42, 1, 23, 1, 3, 0, 'technical'),
(43, 1, 24, 0, 1, 0, 'technical'),
(44, 1, 25, 0, 3, 0, 'technical'),
(45, 1, 26, 0, 1, 0, 'technical'),
(46, 1, 27, 0, 3, 0, 'technical'),
(47, 1, 28, 0, 1, 0, 'technical'),
(48, 1, 29, 0, 4, 0, 'technical'),
(49, 1, 30, 0, 1, 0, 'technical'),
(50, 1, 31, 0, 4, 0, 'technical'),
(51, 1, 32, 2, 2, 1, 'technical'),
(52, 1, 33, 0, 1, 0, 'technical'),
(53, 1, 34, 0, 1, 0, 'technical'),
(54, 1, 35, 0, 2, 0, 'technical'),
(55, 1, 36, 0, 1, 0, 'technical'),
(56, 1, 37, 0, 4, 0, 'technical'),
(57, 1, 38, 0, 1, 0, 'technical'),
(58, 1, 39, 0, 4, 0, 'technical'),
(59, 1, 40, 0, 1, 0, 'technical'),
(60, 1, 41, 0, 3, 0, 'technical'),
(61, 1, 42, 0, 1, 0, 'technical'),
(62, 1, 43, 0, 1, 0, 'technical'),
(63, 1, 44, 0, 1, 0, 'technical'),
(64, 1, 45, 0, 2, 0, 'technical'),
(65, 1, 46, 0, 1, 0, 'technical'),
(66, 1, 47, 0, 4, 0, 'technical'),
(67, 1, 48, 2, 2, 1, 'technical'),
(68, 1, 49, 0, 1, 0, 'technical'),
(69, 1, 50, 0, 2, 0, 'technical'),
(70, 1, 51, 0, 1, 0, 'technical'),
(71, 1, 52, 0, 1, 0, 'technical'),
(72, 1, 53, 0, 4, 0, 'technical'),
(73, 1, 54, 0, 1, 0, 'technical'),
(74, 1, 55, 0, 1, 0, 'technical'),
(75, 1, 56, 0, 4, 0, 'technical'),
(76, 1, 57, 0, 1, 0, 'technical'),
(77, 1, 58, 0, 4, 0, 'technical'),
(78, 1, 59, 0, 1, 0, 'technical'),
(79, 1, 60, 0, 1, 0, 'technical'),
(80, 1, 61, 0, 4, 0, 'technical'),
(81, 1, 113, 0, 1, 0, 'technical'),
(82, 1, 114, 0, 2, 0, 'technical'),
(83, 1, 115, 0, 3, 0, 'technical'),
(84, 1, 116, 0, 1, 0, 'technical'),
(85, 1, 117, 2, 4, 0, 'technical'),
(86, 1, 118, 0, 2, 0, 'technical'),
(87, 1, 119, 0, 3, 0, 'technical'),
(88, 1, 120, 0, 2, 0, 'technical'),
(89, 1, 121, 0, 2, 0, 'technical'),
(90, 1, 122, 3, 4, 0, 'technical'),
(91, 1, 123, 0, 1, 0, 'technical'),
(92, 1, 124, 0, 3, 0, 'technical'),
(93, 1, 125, 1, 1, 1, 'technical'),
(94, 1, 126, 0, 3, 0, 'technical'),
(95, 1, 127, 0, 1, 0, 'technical'),
(96, 1, 128, 0, 3, 0, 'technical'),
(97, 1, 129, 0, 1, 0, 'technical'),
(98, 1, 130, 0, 4, 0, 'technical'),
(99, 1, 131, 0, 1, 0, 'technical'),
(100, 1, 132, 0, 4, 0, 'technical'),
(101, 1, 133, 0, 2, 0, 'technical'),
(102, 1, 134, 1, 1, 1, 'technical'),
(103, 1, 135, 0, 1, 0, 'technical'),
(104, 1, 136, 0, 2, 0, 'technical'),
(105, 1, 137, 0, 1, 0, 'technical'),
(106, 1, 138, 0, 4, 0, 'technical'),
(107, 1, 139, 0, 1, 0, 'technical'),
(108, 1, 140, 1, 4, 0, 'technical'),
(109, 1, 141, 0, 1, 0, 'technical'),
(110, 1, 142, 0, 3, 0, 'technical'),
(111, 1, 143, 0, 1, 0, 'technical'),
(112, 1, 144, 0, 1, 0, 'technical'),
(113, 1, 145, 0, 1, 0, 'technical'),
(114, 1, 146, 0, 2, 0, 'technical'),
(115, 1, 147, 0, 1, 0, 'technical'),
(116, 1, 148, 0, 4, 0, 'technical'),
(117, 1, 149, 0, 2, 0, 'technical'),
(118, 1, 150, 0, 1, 0, 'technical'),
(119, 1, 151, 0, 2, 0, 'technical'),
(120, 1, 152, 1, 1, 1, 'technical'),
(121, 1, 153, 0, 1, 0, 'technical'),
(122, 1, 154, 4, 4, 1, 'technical'),
(123, 1, 155, 0, 1, 0, 'technical'),
(124, 1, 156, 1, 1, 1, 'technical'),
(125, 1, 157, 0, 4, 0, 'technical'),
(126, 1, 158, 1, 1, 1, 'technical'),
(127, 1, 159, 0, 4, 0, 'technical'),
(128, 1, 160, 0, 1, 0, 'technical'),
(129, 1, 161, 0, 1, 0, 'technical'),
(130, 1, 162, 0, 4, 0, 'technical'),
(131, 1, 214, 0, 1, 0, 'technical'),
(132, 1, 215, 0, 2, 0, 'technical'),
(133, 1, 216, 0, 3, 0, 'technical'),
(134, 1, 217, 0, 1, 0, 'technical'),
(135, 1, 218, 0, 4, 0, 'technical'),
(136, 1, 219, 0, 2, 0, 'technical'),
(137, 1, 220, 3, 3, 1, 'technical'),
(138, 1, 221, 0, 2, 0, 'technical'),
(139, 1, 222, 2, 2, 1, 'technical'),
(140, 1, 223, 0, 4, 0, 'technical'),
(141, 1, 224, 1, 1, 1, 'technical'),
(142, 1, 225, 0, 3, 0, 'technical'),
(143, 1, 226, 0, 1, 0, 'technical'),
(144, 1, 227, 0, 3, 0, 'technical'),
(145, 1, 228, 0, 1, 0, 'technical'),
(146, 1, 229, 3, 3, 1, 'technical'),
(147, 1, 230, 0, 1, 0, 'technical'),
(148, 1, 231, 0, 4, 0, 'technical'),
(149, 1, 232, 0, 1, 0, 'technical'),
(150, 1, 233, 0, 4, 0, 'technical'),
(151, 1, 234, 0, 2, 0, 'technical'),
(152, 1, 235, 0, 1, 0, 'technical'),
(153, 1, 236, 0, 1, 0, 'technical'),
(154, 1, 237, 0, 2, 0, 'technical'),
(155, 1, 238, 2, 1, 0, 'technical'),
(156, 1, 239, 4, 4, 1, 'technical'),
(157, 1, 240, 0, 1, 0, 'technical'),
(158, 1, 241, 0, 4, 0, 'technical'),
(159, 1, 242, 0, 1, 0, 'technical'),
(160, 1, 243, 0, 3, 0, 'technical'),
(161, 1, 244, 0, 1, 0, 'technical'),
(162, 1, 245, 0, 1, 0, 'technical'),
(163, 1, 246, 0, 1, 0, 'technical'),
(164, 1, 247, 2, 2, 1, 'technical'),
(165, 1, 248, 0, 1, 0, 'technical'),
(166, 1, 249, 0, 4, 0, 'technical'),
(167, 1, 250, 0, 2, 0, 'technical'),
(168, 1, 251, 1, 1, 1, 'technical'),
(169, 1, 252, 0, 2, 0, 'technical'),
(170, 1, 253, 0, 1, 0, 'technical'),
(171, 1, 254, 0, 1, 0, 'technical'),
(172, 1, 255, 4, 4, 1, 'technical'),
(173, 1, 256, 0, 1, 0, 'technical'),
(174, 1, 257, 0, 1, 0, 'technical'),
(175, 1, 258, 0, 4, 0, 'technical'),
(176, 1, 259, 0, 1, 0, 'technical'),
(177, 1, 260, 0, 4, 0, 'technical'),
(178, 1, 261, 0, 1, 0, 'technical'),
(179, 1, 262, 0, 1, 0, 'technical'),
(180, 1, 263, 1, 4, 0, 'technical'),
(181, 1, 264, 0, 3, 0, 'technical'),
(182, 1, 265, 0, 2, 0, 'technical'),
(183, 1, 266, 0, 3, 0, 'technical'),
(184, 1, 267, 0, 2, 0, 'technical'),
(185, 1, 268, 0, 2, 0, 'technical'),
(186, 1, 269, 0, 2, 0, 'technical'),
(187, 1, 270, 0, 2, 0, 'technical'),
(188, 1, 271, 0, 1, 0, 'technical'),
(189, 1, 272, 0, 2, 0, 'technical'),
(190, 1, 273, 0, 2, 0, 'technical'),
(191, 1, 274, 0, 2, 0, 'technical'),
(192, 1, 275, 0, 1, 0, 'technical'),
(193, 1, 276, 0, 1, 0, 'technical'),
(194, 1, 277, 0, 1, 0, 'technical'),
(195, 1, 278, 0, 1, 0, 'technical'),
(196, 1, 279, 0, 2, 0, 'technical'),
(197, 1, 280, 0, 1, 0, 'technical'),
(198, 1, 281, 0, 2, 0, 'technical'),
(199, 1, 282, 0, 1, 0, 'technical'),
(200, 1, 283, 0, 1, 0, 'technical'),
(201, 1, 284, 0, 1, 0, 'technical'),
(202, 1, 285, 0, 1, 0, 'technical'),
(203, 1, 286, 0, 1, 0, 'technical'),
(204, 1, 287, 0, 1, 0, 'technical');

-- --------------------------------------------------------

--
-- Table structure for table `wrong_answers`
--

CREATE TABLE `wrong_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_answer` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coding_challenges`
--
ALTER TABLE `coding_challenges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coding_submissions`
--
ALTER TABLE `coding_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `challenge_id` (`challenge_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_results`
--
ALTER TABLE `student_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wrong_answers`
--
ALTER TABLE `wrong_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coding_challenges`
--
ALTER TABLE `coding_challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `coding_submissions`
--
ALTER TABLE `coding_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_results`
--
ALTER TABLE `student_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `wrong_answers`
--
ALTER TABLE `wrong_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coding_submissions`
--
ALTER TABLE `coding_submissions`
  ADD CONSTRAINT `coding_submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `coding_submissions_ibfk_2` FOREIGN KEY (`challenge_id`) REFERENCES `coding_challenges` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wrong_answers`
--
ALTER TABLE `wrong_answers`
  ADD CONSTRAINT `wrong_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wrong_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
