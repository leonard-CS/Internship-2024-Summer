<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $filename = uniqid() . '.txt'; // Generate a unique filename

    // Save the post content to a file
    file_put_contents('posts/' . $filename, $content);

    // Save metadata to the database
    $stmt = $conn->prepare("INSERT INTO posts (title, filename) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $filename);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: index.php");
?>
