<?php
$servername = "mysql";
$username = "myuser";
$password = "mypassword";
$dbname = "mydatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Users WHERE id=$id");
}

// Handle insert request
if (isset($_POST['insert'])) {
    $firstname = $_POST['new_firstname'];
    $lastname = $_POST['new_lastname'];
    $email = $_POST['new_email'];

    $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $firstname, $lastname, $email);
    $stmt->execute();
    $stmt->close();
}

// Fetch data
$result = $conn->query("SELECT * FROM Users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Data</title>
</head>
<body>
    <h1>Manage Users</h1>

    <!-- Form to insert a new user -->
    <h2>Insert New User</h2>
    <form action="manage_data.php" method="post">
        <label for="new_firstname">First Name:</label>
        <input type="text" id="new_firstname" name="new_firstname" required><br><br>

        <label for="new_lastname">Last Name:</label>
        <input type="text" id="new_lastname" name="new_lastname" required><br><br>

        <label for="new_email">Email:</label>
        <input type="email" id="new_email" name="new_email" required><br><br>

        <input type="submit" name="insert" value="Insert">
    </form>

    <!-- Display data table -->
    <h2>Users Table</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form action="manage_data.php" method="post">
                <td><?php echo $row['id']; ?></td>
                <td><input type="text" name="firstname" value="<?php echo $row['firstname']; ?>" required></td>
                <td><input type="text" name="lastname" value="<?php echo $row['lastname']; ?>" required></td>
                <td><input type="email" name="email" value="<?php echo $row['email']; ?>" required></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <a href="update_form.php?id=<?php echo $row['id']; ?>">Update</a>
                    <a href="manage_data.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
