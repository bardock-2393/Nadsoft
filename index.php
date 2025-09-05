<?php
require_once 'classes/Member.php';

try {
    $member = new Member();
    $allMembers = $member->getAllMembers();
    $tree = $member->buildTree($allMembers);
    $treeHTML = $member->generateTreeHTML($tree);
} catch (Exception $e) {
    $error = $e->getMessage();
    $treeHTML = '<p style="color: red;">Error: ' . htmlspecialchars($error) . '</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        
        .members-tree {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        
        .members-tree ul {
            list-style: none;
            padding-left: 20px;
            margin: 0;
        }
        
        .members-tree li {
            margin: 8px 0;
            padding: 5px 10px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .members-tree li strong {
            color: #007bff;
        }
        
        .members-tree li small {
            color: #6c757d;
            font-size: 0.85em;
        }
        
        .add-member-btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        
        .add-member-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,123,255,0.4);
        }
        
        .add-member-btn i {
            margin-right: 8px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .modal-header h2 {
            margin: 0;
            color: #333;
        }
        
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close:hover {
            color: #000;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group select,
        .form-group input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group select:focus,
        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .form-group .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            display: none;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-users"></i> Member Management System</h1>
        
        <div class="members-tree">
            <h3><i class="fas fa-sitemap"></i> Members Tree Structure</h3>
            <?php echo $treeHTML; ?>
        </div>
        
        <div style="text-align: center;">
            <button class="add-member-btn" onclick="openModal()">
                <i class="fas fa-plus"></i> Add Member
            </button>
        </div>
    </div>
    
    <!-- Modal -->
    <div id="memberModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Add New Member</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            
            <div class="success-message" id="successMessage"></div>
            <div class="error-message" id="errorMessage"></div>
            
            <form id="memberForm">
                <div class="form-group">
                    <label for="parentId">Parent Member:</label>
                    <select id="parentId" name="parentId">
                        <option value="">Select Parent (Optional)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="memberName">Member Name: <span style="color: red;">*</span></label>
                    <input type="text" id="memberName" name="memberName" placeholder="Enter member name" required>
                    <div class="error" id="nameError"></div>
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Saving member...</p>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load parent members for dropdown
            loadParentMembers();
            
            // Form submission
            $('#memberForm').on('submit', function(e) {
                e.preventDefault();
                saveMember();
            });
            
            // Close modal when clicking outside
            $(window).on('click', function(e) {
                if (e.target.id === 'memberModal') {
                    closeModal();
                }
            });
            
            // Real-time validation
            $('#memberName').on('input', function() {
                validateName();
            });
        });
        
        function openModal() {
            $('#memberModal').show();
            $('#memberName').focus();
            clearMessages();
            clearForm();
        }
        
        function closeModal() {
            $('#memberModal').hide();
            clearMessages();
            clearForm();
        }
        
        function clearForm() {
            $('#memberForm')[0].reset();
            $('#nameError').hide();
        }
        
        function clearMessages() {
            $('#successMessage').hide();
            $('#errorMessage').hide();
        }
        
        function loadParentMembers() {
            $.ajax({
                url: 'ajax/get_members.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const select = $('#parentId');
                        select.empty();
                        select.append('<option value="">Select Parent (Optional)</option>');
                        
                        response.data.forEach(function(member) {
                            select.append(`<option value="${member.Id}">${member.Name}</option>`);
                        });
                    }
                },
                error: function() {
                    console.error('Error loading parent members');
                }
            });
        }
        
        function validateName() {
            const name = $('#memberName').val().trim();
            const errorDiv = $('#nameError');
            
            if (name === '') {
                errorDiv.text('Name is required').show();
                return false;
            } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                errorDiv.text('Name must contain only letters and spaces').show();
                return false;
            } else if (name.length < 2) {
                errorDiv.text('Name must be at least 2 characters long').show();
                return false;
            } else {
                errorDiv.hide();
                return true;
            }
        }
        
        function saveMember() {
            if (!validateName()) {
                return;
            }
            
            const formData = {
                name: $('#memberName').val().trim(),
                parentId: $('#parentId').val() || null
            };
            
            $('#loading').show();
            $('#memberForm').hide();
            clearMessages();
            
            $.ajax({
                url: 'ajax/add_member.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#loading').hide();
                    $('#memberForm').show();
                    
                    if (response.success) {
                        $('#successMessage').text('Member added successfully!').show();
                        
                        // Append new member to tree structure
                        appendMemberToTree(response.data);
                        
                        // Reload parent members dropdown
                        loadParentMembers();
                        
                        // Clear form after successful submission
                        setTimeout(function() {
                            clearForm();
                        }, 1000);
                        
                    } else {
                        $('#errorMessage').text(response.message || 'Error adding member').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    $('#memberForm').show();
                    $('#errorMessage').text('Error: ' + error).show();
                }
            });
        }
        
        function appendMemberToTree(memberData) {
            const memberId = memberData.Id;
            const memberName = memberData.Name;
            const parentId = memberData.ParentId;
            
            if (parentId === null) {
                // Add as root level member
                const newLi = $(`<li data-id="${memberId}"><strong>${memberName}</strong> <small>(ID: ${memberId})</small></li>`);
                $('.members-tree ul').first().append(newLi);
            } else {
                // Find parent and add as child
                const parentLi = $(`.members-tree li[data-id="${parentId}"]`);
                if (parentLi.length > 0) {
                    let parentUl = parentLi.find('> ul');
                    if (parentUl.length === 0) {
                        parentUl = $('<ul></ul>');
                        parentLi.append(parentUl);
                    }
                    const newLi = $(`<li data-id="${memberId}"><strong>${memberName}</strong> <small>(ID: ${memberId})</small></li>`);
                    parentUl.append(newLi);
                }
            }
        }
    </script>
</body>
</html>
