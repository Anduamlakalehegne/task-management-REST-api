<?php

class ApiTester {
    private $baseUrl = 'http://localhost:8000';
    
    public function testCreateTask() {
        echo "Testing POST /tasks\n";
        echo "===================\n";
        
        $data = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending'
        ];
        
        $response = $this->makeRequest('POST', '/tasks', $data);
        echo "Response: " . $response . "\n\n";
        
        return json_decode($response, true);
    }
    
    public function testGetAllTasks() {
        echo "Testing GET /tasks\n";
        echo "==================\n";
        
        $response = $this->makeRequest('GET', '/tasks');
        echo "Response: " . $response . "\n\n";
        
        return json_decode($response, true);
    }
    
    public function testGetTasksByStatus() {
        echo "Testing GET /tasks?status=completed\n";
        echo "==================================\n";
        
        $response = $this->makeRequest('GET', '/tasks?status=completed');
        echo "Response: " . $response . "\n\n";
        
        return json_decode($response, true);
    }
    
    public function testGetTaskById($id) {
        echo "Testing GET /tasks/$id\n";
        echo "======================\n";
        
        $response = $this->makeRequest('GET', "/tasks/$id");
        echo "Response: " . $response . "\n\n";
        
        return json_decode($response, true);
    }
    
    public function testUpdateTask($id) {
        echo "Testing PUT /tasks/$id\n";
        echo "======================\n";
        
        $data = [
            'title' => 'Updated Test Task',
            'description' => 'This task has been updated',
            'status' => 'in-progress'
        ];
        
        $response = $this->makeRequest('PUT', "/tasks/$id", $data);
        echo "Response: " . $response . "\n\n";
        
        return json_decode($response, true);
    }
    
    public function testDeleteTask($id) {
        echo "Testing DELETE /tasks/$id\n";
        echo "========================\n";
        
        $response = $this->makeRequest('DELETE', "/tasks/$id");
        echo "Response: " . $response . "\n\n";
        
        return json_decode($response, true);
    }
    
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if ($data && in_array($method, ['POST', 'PUT'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $response;
    }
    
    public function runAllTests() {
        echo "Starting API Tests\n";
        echo "==================\n\n";
        
        // Test creating a task
        $createResult = $this->testCreateTask();
        $taskId = $createResult['data']['id'] ?? 1;
        
        // Test getting all tasks
        $this->testGetAllTasks();
        
        // Test getting tasks by status
        $this->testGetTasksByStatus();
        
        // Test getting a specific task
        $this->testGetTaskById($taskId);
        
        // Test updating a task
        $this->testUpdateTask($taskId);
        
        // Test deleting a task
        $this->testDeleteTask($taskId);
        
        echo "All tests completed!\n";
    }
}

// Run tests if script is executed directly
if (php_sapi_name() === 'cli' || isset($_GET['run'])) {
    $tester = new ApiTester();
    $tester->runAllTests();
} 