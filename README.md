# Member Management System

A PHP-based member management system that displays members in a hierarchical tree structure with the ability to add new members dynamically.

## Features

- **Hierarchical Tree Structure**: Displays members in a parent-child relationship using recursive functions
- **Dynamic Member Addition**: Add new members with optional parent selection
- **Real-time Updates**: New members are added to the tree without page refresh using jQuery AJAX
- **Form Validation**: Client-side and server-side validation for member names
- **Responsive Design**: Modern, clean interface with smooth animations
- **OOP Architecture**: Built using PHP Object-Oriented Programming concepts
- **PDO Database Layer**: Secure database operations using PHP PDO

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- jQuery (loaded via CDN)

## Database Configuration

The application uses the following database settings:
- **Host**: 172.25.118.195
- **Port**: 3306
- **Username**: root
- **Password**: RootPassword123!
- **Database**: nadsoft

## Installation

1. **Setup Database**:
   ```bash
   php setup_database.php
   ```
   This will create the database and Members table with sample data.

2. **Access Application**:
   Open your browser and navigate to:
   ```
   http://localhost/test/nadsoft/
   ```

## Database Schema

### Members Table
```sql
CREATE TABLE Members (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    CreatedDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    Name VARCHAR(50) NOT NULL UNIQUE,
    ParentId INT NULL,
    FOREIGN KEY (ParentId) REFERENCES Members(Id) ON DELETE SET NULL
);
```

## Project Structure

```
nadsoft/
├── config/
│   └── database.php          # Database configuration
├── classes/
│   ├── Database.php          # Database connection class
│   └── Member.php            # Member model class
├── ajax/
│   ├── get_members.php       # AJAX endpoint for fetching members
│   └── add_member.php        # AJAX endpoint for adding members
├── index.php                 # Main application page
├── setup_database.php        # Database setup script
└── README.md                 # This file
```

## Key Features Implementation

### 1. PHP OOP Concepts
- **Singleton Pattern**: Database connection class uses singleton pattern
- **Encapsulation**: Member class with private properties and public methods
- **Inheritance**: Proper class structure with clear separation of concerns

### 2. Recursive Functions
- **Tree Building**: `buildTree()` method recursively builds hierarchical structure
- **HTML Generation**: `generateTreeHTML()` method recursively generates HTML tree

### 3. jQuery AJAX
- **Dynamic Loading**: Parent members loaded via AJAX for dropdown
- **Form Submission**: Member creation handled via AJAX without page refresh
- **Real-time Updates**: New members appended to tree structure dynamically

### 4. Form Validation
- **Client-side**: Real-time validation using jQuery
- **Server-side**: Comprehensive validation in PHP
- **Name Validation**: Only letters and spaces allowed, 2-50 characters

## Usage

1. **View Members**: The main page displays all members in a tree structure
2. **Add Member**: Click "Add Member" button to open the modal
3. **Select Parent**: Choose a parent member from the dropdown (optional)
4. **Enter Name**: Type the member name (letters and spaces only)
5. **Save**: Click "Save Changes" to add the member
6. **View Result**: New member appears in the tree structure immediately

## Technical Details

### Database Connection
- Uses PDO for secure database operations
- Singleton pattern ensures single database connection
- Proper error handling and exception management

### Security Features
- Prepared statements prevent SQL injection
- Input validation and sanitization
- CSRF protection considerations
- XSS prevention with htmlspecialchars()

### Performance Optimizations
- Efficient recursive tree building
- Minimal database queries
- Optimized HTML generation
- CDN-loaded jQuery for faster loading

## Browser Compatibility

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## License

This project is created for testing purposes.
