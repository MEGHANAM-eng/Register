<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Server-side validation
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword) || empty($dob) || empty($gender) || empty($address)) {
        echo "Error: All fields are required!";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format!";
        exit;
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "Error: Invalid phone number! Please enter a 10-digit number.";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "Error: Passwords do not match!";
        exit;
    }

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $database = "registration_db";

    // Create database connection
    $conn = new mysqli($servername, $username, $dbpassword, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email already exists
    $checkEmailSQL = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmailSQL);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "Error: This email is already registered!";
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    // Insert data into the database
    $sql = "INSERT INTO users (name, email, phone, password, dob, gender, address, agreed_terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $email, $phone, $hashedPassword, $dob, $gender, $address, $terms);

    if ($stmt->execute()) {
        // Display only the success message
        echo "<h2>Registration Successful</h2>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid Request!";
}
?>
