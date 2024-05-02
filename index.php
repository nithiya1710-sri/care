<?php
// Replace 'localhost', 'username', 'password', and 'database_name' with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database_name = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if email and password are provided
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Sanitize user inputs to prevent SQL injection
    $email = $conn->real_escape_string($_POST['email']);

    // Prepare and bind SQL statement with a parameter
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if there's a matching email in the database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($_POST['password'], $hashed_password)) {
            // Password is correct, redirect to dashboard
            header("Location: dashboard.php");
            exit(); // Ensure script execution stops after redirect
        } else {
            // Password is incorrect
            echo "Invalid email or password";
        }
    } else {
        // Email doesn't exist
        echo "Invalid email or password";
    }

    // Close statement
    $stmt->close();
} else {
    // Email or password not provided
    echo "Email and password are required";
}

// Close connection
$conn->close();
?>