<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if (!isset($id) || !is_numeric($id)) {
    die("Invalid ID provided.");
}

// Get the filename from the database
$stmt = $conn->prepare("SELECT filename FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Post not found.");
}

$row = $result->fetch_assoc();
$filename = $row['filename'];
$file_path = 'posts/' . $filename;

// Delete the file if it exists
if (file_exists($file_path)) {
    if (is_file($file_path)) {
        unlink($file_path);
    } else {
        die("The specified path is a directory, not a file.");
    }
} else {
    die("File not found.");
}

// Delete the metadata from the database
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect to the main page
header("Location: index.php");
exit();
?>
