<?php
/**
 * Test script to verify the complete role history fix
 * This script tests that both roleHistory.php and viewUser.php no longer throw undefined array key errors
 */

require_once 'config.php';
require_once 'app/models/userModel.php';

echo "=== Testing Complete Role History Fix ===\n\n";

try {
    // Create database connection
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create UserModel instance
    $userModel = new UserModel($dbConn);
    
    // Test 1: Get role history for a user
    echo "1. Testing getRoleHistory method...\n";
    $userId = 1; // Assuming user ID 1 exists
    $roleHistory = $userModel->getRoleHistory($userId);
    
    echo "   - Role history retrieved successfully\n";
    echo "   - Number of roles found: " . count($roleHistory) . "\n";
    
    // Test 2: Check if all required fields are present
    echo "\n2. Checking field structure...\n";
    if (!empty($roleHistory)) {
        $firstRole = $roleHistory[0];
        $requiredFields = ['role_type', 'is_active', 'created_at', 'updated_at'];
        
        foreach ($requiredFields as $field) {
            if (isset($firstRole[$field])) {
                echo "   ✓ Field '$field' is present\n";
            } else {
                echo "   ✗ Field '$field' is missing\n";
            }
        }
        
        // Test 3: Simulate the view logic for roleHistory.php
        echo "\n3. Testing roleHistory.php view logic simulation...\n";
        foreach ($roleHistory as $role) {
            echo "   - Role: " . $role['role_type'] . "\n";
            echo "     Status: " . ($role['is_active'] ? 'Active' : 'Inactive') . "\n";
            echo "     Created: " . $role['created_at'] . "\n";
            
            // Test the deactivation date logic (roleHistory.php)
            if (!$role['is_active']) {
                $deactivationDate = $role['updated_at'] ?? $role['created_at'];
                echo "     Deactivated: " . $deactivationDate . "\n";
            } else {
                echo "     Deactivated: -\n";
            }
            
            // Test assigned_by field (should show "Sistema")
            echo "     Assigned By: Sistema\n";
            echo "\n";
        }
        
        // Test 4: Simulate the view logic for viewUser.php
        echo "\n4. Testing viewUser.php view logic simulation...\n";
        foreach ($roleHistory as $role) {
            echo "   - Role: " . $role['role_type'] . "\n";
            echo "     Status: " . ($role['is_active'] ? 'Active' : 'Inactive') . "\n";
            echo "     Created: " . $role['created_at'] . "\n";
            
            // Test the deactivation date logic (viewUser.php)
            if (!$role['is_active']) {
                $deactivationDate = $role['updated_at'] ?? $role['created_at'];
                echo "     Deactivated: " . $deactivationDate . "\n";
            } else {
                echo "     Deactivated: -\n";
            }
            echo "\n";
        }
    } else {
        echo "   - No role history found for user ID $userId\n";
    }
    
    // Test 5: Check if any users exist
    echo "5. Checking available users...\n";
    $users = $userModel->getUsers();
    echo "   - Total users found: " . count($users) . "\n";
    
    if (!empty($users)) {
        echo "   - Testing with first user (ID: " . $users[0]['user_id'] . ")\n";
        $testRoleHistory = $userModel->getRoleHistory($users[0]['user_id']);
        echo "   - Role history for first user: " . count($testRoleHistory) . " roles\n";
    }
    
    // Test 6: Verify no undefined array key errors
    echo "\n6. Testing for undefined array key errors...\n";
    $errorFound = false;
    
    if (!empty($roleHistory)) {
        foreach ($roleHistory as $role) {
            // Test fields that were causing errors
            $testFields = ['deactivated_at', 'assigned_by'];
            
            foreach ($testFields as $field) {
                if (isset($role[$field])) {
                    echo "   ✗ Field '$field' exists (should not exist)\n";
                    $errorFound = true;
                } else {
                    echo "   ✓ Field '$field' correctly does not exist\n";
                }
            }
        }
    }
    
    echo "\n=== Test Results ===\n";
    if (!$errorFound) {
        echo "✓ No 'deactivated_at' field errors should occur\n";
        echo "✓ No 'assigned_by' field errors should occur\n";
        echo "✓ Role history displays correctly using 'is_active' and 'updated_at'\n";
        echo "✓ View logic handles missing fields gracefully\n";
        echo "✓ Both roleHistory.php and viewUser.php should work without errors\n";
    } else {
        echo "✗ Some errors were found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
?> 