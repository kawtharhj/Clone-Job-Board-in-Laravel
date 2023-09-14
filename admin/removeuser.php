<?php
// check if user ID is provided
if (isset($_GET['id'])) {
    // include database connection
    require 'db.php';
    
    // get user ID from GET parameter
    $user_id = $_GET['id'];
    
    // prepare and execute SQL query to delete user by ID
    $stmt = $connect->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // redirect back to user list page after deleting user
    header('Location: userlist.php');
    exit();
} else {
    // if no user ID is provided, redirect to user list page
    header('Location: userlist.php');
    exit();
}
?>
