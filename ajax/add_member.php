<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Member.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

try {
    // Get and validate input data
    $name = trim($_POST['name'] ?? '');
    $parentId = !empty($_POST['parentId']) ? (int)$_POST['parentId'] : null;
    
    // Server-side validation
    if (empty($name)) {
        throw new Exception('Name is required');
    }
    
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        throw new Exception('Name must contain only letters and spaces');
    }
    
    if (strlen($name) < 2) {
        throw new Exception('Name must be at least 2 characters long');
    }
    
    if (strlen($name) > 50) {
        throw new Exception('Name must not exceed 50 characters');
    }
    
    // Create member
    $member = new Member();
    $newMemberId = $member->createMember($name, $parentId);
    
    // Get the created member data
    $newMember = $member->getMemberById($newMemberId);
    
    if (!$newMember) {
        throw new Exception('Failed to retrieve created member');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Member created successfully',
        'data' => $newMember
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
