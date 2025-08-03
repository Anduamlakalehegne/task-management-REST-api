<?php

require_once __DIR__ . '/database/Database.php';
require_once __DIR__ . '/models/Task.php';

echo "Initializing database...\n";

// Initialize database
$db = Database::getInstance();
$db->createTables();

echo "Database tables created successfully!\n";

// Create sample tasks
$taskModel = new Task();

$sampleTasks = [
    [
        'title' => 'Complete API Documentation',
        'description' => 'Write comprehensive API documentation with examples',
        'status' => 'pending'
    ],
    [
        'title' => 'Implement User Authentication',
        'description' => 'Add JWT-based authentication to the API',
        'status' => 'in-progress'
    ],
    [
        'title' => 'Write Unit Tests',
        'description' => 'Create unit tests for all API endpoints',
        'status' => 'completed'
    ]
];

foreach ($sampleTasks as $taskData) {
    $id = $taskModel->create($taskData);
    echo "Created task: {$taskData['title']} (ID: $id)\n";
}

echo "\nDatabase initialization completed!\n";
echo "Sample tasks have been created.\n"; 