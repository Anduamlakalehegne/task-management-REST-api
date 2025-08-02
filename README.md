# Task Management REST API

A simple yet robust Task Management REST API built with pure PHP and SQLite, designed to demonstrate clean code architecture and best practices.

## 🚀 Features

- **Full CRUD Operations**: Create, Read, Update, Delete tasks
- **Status Filtering**: Filter tasks by status (pending, in-progress, completed)
- **Input Validation**: Comprehensive validation with meaningful error messages
- **RESTful Design**: Follows REST API conventions
- **Docker Support**: Easy deployment with Docker and Docker Compose
- **SQLite Database**: Lightweight, file-based database
- **MVC Architecture**: Clean separation of concerns
- **Error Handling**: Proper HTTP status codes and error responses

## 📁 Project Structure

```
├── database/
│   ├── Database.php      # Database connection and initialization
│   └── tasks.db         # SQLite database file (created automatically)
├── models/
│   └── Task.php         # Task model with CRUD operations
├── controllers/
│   └── TaskController.php # HTTP request handling
├── routes/
│   └── Router.php       # URL routing and parameter extraction
├── index.php            # Main application entry point
├── init_db.php          # Database initialization script
├── test-api.php         # API testing script
├── postman-collection.json # Postman collection for testing
├── Dockerfile           # Docker configuration
├── docker-compose.yml   # Docker Compose configuration
└── README.md           # This file
```

## 📋 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/tasks` | Retrieve all tasks |
| GET | `/tasks?status={status}` | Filter tasks by status |
| GET | `/tasks/{id}` | Retrieve a specific task |
| POST | `/tasks` | Create a new task |
| PUT | `/tasks/{id}` | Update an existing task |
| DELETE | `/tasks/{id}` | Delete a task |


## 🗄️ Database Schema

```sql
CREATE TABLE tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'pending' CHECK(status IN ('pending', 'in-progress', 'completed')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## 🐳 Quick Start with Docker

### Prerequisites
- Docker
- Docker Compose (optional)

### Installation & Running

1. **Clone or download the project**
   ```bash
   git clone <repository-url>
   cd task-management-REST-api
   ```

2. **Build and run with Docker Compose**
   ```bash
   docker-compose up --build
   ```

3. **Access the API**
   - API Base URL: `http://localhost:8080`
   - The database will be automatically initialized with sample data

## Manual Installation (Without Docker)

### Prerequisites
- PHP 8.0 or higher
- SQLite extension for PHP (usually included with PHP)

### Installation Steps

1. **Clone the project**
   ```bash
   git clone <repository-url>
   cd task-management-REST-api
   ```

2. **Initialize the database**
   ```bash
   php init_db.php
   ```

3. **Start the PHP development server**
   ```bash
   php -S localhost:8000
   ```

4. **Access the API**
   - API Base URL: `http://localhost:8000`

### Alternative: Using Apache/Nginx

If you prefer using Apache or Nginx:

1. **Install a local web server** (XAMPP, WAMP, MAMP, or standalone Apache/Nginx)
2. **Configure the web server**
   - Point document root to the project directory
   - Ensure URL rewriting is enabled (mod_rewrite for Apache)
   - Make sure the `database/` directory is writable
3. **Access the API** via your configured domain/path

## API Endpoints

### Base URL
```
http://localhost:8080
```

### Endpoints

#### 1. Get All Tasks
```http
GET /tasks
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Complete API Documentation",
      "description": "Write comprehensive API documentation",
      "status": "pending",
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ]
}
```

#### 2. Get Tasks by Status (Filtering)
```http
GET /tasks?status=completed
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 3,
      "title": "Write Unit Tests",
      "description": "Create unit tests for all endpoints",
      "status": "completed",
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ]
}
```

#### 3. Get Task by ID
```http
GET /tasks/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Complete API Documentation",
    "description": "Write comprehensive API documentation",
    "status": "pending",
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:00:00"
  }
}
```

#### 4. Create Task
```http
POST /tasks
Content-Type: application/json

{
  "title": "New Task",
  "description": "This is a new task",
  "status": "pending"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Task created successfully",
  "data": {
    "id": 4,
    "title": "New Task",
    "description": "This is a new task",
    "status": "pending",
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:00:00"
  }
}
```

#### 5. Update Task
```http
PUT /tasks/{id}
Content-Type: application/json

{
  "title": "Updated Task",
  "description": "This task has been updated",
  "status": "in-progress"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Task updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Task",
    "description": "This task has been updated",
    "status": "in-progress",
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:30:00"
  }
}
```

#### 6. Delete Task
```http
DELETE /tasks/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Task deleted successfully"
}
```

## Error Responses

### Validation Error (400)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": [
    "Title is required",
    "Status must be pending, in-progress, or completed"
  ]
}
```

### Not Found Error (404)
```json
{
  "success": false,
  "message": "Task not found"
}
```

### Route Not Found (404)
```json
{
  "success": false,
  "message": "Route not found"
}
```

## Testing

### Using the Test Script
```bash
php test-api.php
```

### Using Postman
1. Import the `postman-collection.json` file into Postman
2. Set the `base_url` variable to `http://localhost:8080`
3. Run the collection

### Using cURL Examples

**Create a task:**
```bash
curl -X POST http://localhost:8080/tasks \
  -H "Content-Type: application/json" \
  -d '{"title": "Test Task", "description": "Test description", "status": "pending"}'
```

**Get all tasks:**
```bash
curl http://localhost:8080/tasks
```

**Get tasks by status:**
```bash
curl "http://localhost:8080/tasks?status=completed"
```

**Update a task:**
```bash
curl -X PUT http://localhost:8080/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"title": "Updated Task", "description": "Updated description", "status": "in-progress"}'
```

**Delete a task:**
```bash
curl -X DELETE http://localhost:8080/tasks/1
```

## Status Values

- `pending`: Task is not yet started
- `in-progress`: Task is currently being worked on
- `completed`: Task has been finished

