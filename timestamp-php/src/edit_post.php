<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = htmlspecialchars($_POST['title']);
    $new_content = htmlspecialchars($_POST['content']);

    // Update the database
    $stmt = $conn->prepare("UPDATE posts SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $new_title, $id);
    $stmt->execute();
    $stmt->close();

    // Update the file content
    $result = $conn->query("SELECT filename FROM posts WHERE id = $id");
    $row = $result->fetch_assoc();
    $filename = $row['filename'];
    file_put_contents('posts/' . $filename, $new_content);

    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT title, filename FROM posts WHERE id = $id");
$row = $result->fetch_assoc();
$title = $row['title'];
$filename = $row['filename'];
$content = file_exists('posts/' . $filename) ? htmlspecialchars(file_get_contents('posts/' . $filename)) : '';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>
    <form action="edit_post.php?id=<?php echo $id; ?>" method="POST">
        <h2>Title</h2>
        <textarea name="title" rows="1" cols="100" placeholder="Enter your title here..."></textarea>
        <h2>Content</h2>
        <textarea name="content" rows="10" cols="100" placeholder="Enter your content here..."></textarea>
        <br>
        <button type="submit">Update Post</button>
    </form>
</body>
</html>
