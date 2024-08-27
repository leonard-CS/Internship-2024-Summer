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
    $action = $_POST['action'];

    if ($action == 'update') {
        $new_title = htmlspecialchars($_POST['title']);
        $new_content = htmlspecialchars($_POST['content']);
    
        // Update the database
        $stmt = $conn->prepare("UPDATE posts SET title = ? WHERE id = ?");
        $stmt->bind_param("si", $new_title, $id);
        $stmt->execute();
        $stmt->close();
    
        // Update the file content
        $filename = '';
        $result = $conn->query("SELECT filename FROM posts WHERE id = $id");
        if ($result && $row = $result->fetch_assoc()) {
            $filename = $row['filename'];
        }
        file_put_contents('posts/' . $filename, $new_content);
    
        // Redirect after update
        header("Location: edit_post.php?id=$id");
        exit();
    } elseif ($action == 'timestamp') {
        // Create timestamp file
        $timestamp_file = 'posts/' . $id . '_timestamp.txt';
        $current_time = date('Y-m-d H:i:s');
        file_put_contents($timestamp_file, "Timestamp: $current_time");

        // Get the post filename
        $filename = '';
        $result = $conn->query("SELECT filename FROM posts WHERE id = $id");
        if ($result && $row = $result->fetch_assoc()) {
            $filename = $row['filename'];
        }

        // Create a zip file containing the post content and timestamp
        $zip = new ZipArchive();
        $zip_filename = 'posts/' . $id . '_backup.zip';

        if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
            $zip->addFile('posts/' . $filename, $filename);
            $zip->addFile($timestamp_file, basename($timestamp_file));
            $zip->close();

            // Get zip file details
            $file_size = filesize($zip_filename);
            $file_type = mime_content_type($zip_filename);

            // Prepare and execute the insert statement
            $stmt = $conn->prepare("INSERT INTO timestampzips (post_id, filename, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
            
            // Bind parameters
            $zip_filename_basename = basename($zip_filename);
            $stmt->bind_param("isssi", $id, $zip_filename_basename, $zip_filename, $file_type, $file_size);

            $stmt->execute();
            $stmt->close();

            // Clean up timestamp file
            unlink($timestamp_file);
        } else {
            echo "Failed to create zip file.";
        }

        // Redirect after timestamp
        header("Location: edit_post.php?id=$id");
        exit();
    }
}

// Retrieve post data
$result = $conn->query("SELECT title, filename FROM posts WHERE id = $id");
if ($result && $row = $result->fetch_assoc()) {
    $title = $row['title'];
    $filename = $row['filename'];
    $content = file_exists('posts/' . $filename) ? htmlspecialchars(file_get_contents('posts/' . $filename)) : '';
} else {
    $title = '';
    $content = '';
}

// Retrieve timestamp zip files
$stmt = $conn->prepare("SELECT id, filename, created_at FROM timestampzips WHERE post_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$timestamp_result = $stmt->get_result();

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
        <textarea name="title" rows="1" cols="100" placeholder="Enter your title here..."><?php echo htmlspecialchars($title); ?></textarea>
        <h2>Content</h2>
        <textarea name="content" rows="10" cols="100" placeholder="Enter your content here..."><?php echo htmlspecialchars($content); ?></textarea>
        <br>
        <button type="submit" name="action" value="update">Update Post</button>
        <button type="submit" name="action" value="timestamp">Timestamp</button>
    </form>

    <h2>Timestamp ZIP Files</h2>
    <?php if ($timestamp_result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $timestamp_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['filename']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No timestamp ZIP files found for this post.</p>
    <?php endif; ?>
</body>
</html>
