<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Member.php';

try {
    $member = new Member();
    $members = $member->getMembersForDropdown();
    
    echo json_encode([
        'success' => true,
        'data' => $members
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
