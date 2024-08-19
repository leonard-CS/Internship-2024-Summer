<?php
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, filename, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $file_path = 'posts/' . $row['filename'];
        $content = file_exists($file_path) ? htmlspecialchars(file_get_contents($file_path)) : 'Content not found';
        echo "<tr>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . $content . "</td>
                <td>" . htmlspecialchars($row['created_at']) . "</td>
                <td>
                    <a href='edit_post.php?id=" . $row['id'] . "'>Edit</a> | 
                    <a href='delete_post.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No posts available.";
}

$conn->close();
?>
