<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'content' is set and not empty
    if (isset($_POST['header']) && !empty($_POST['header']) && isset($_POST['body']) && !empty($_POST['body'])) {
        // Get the content from the POST request
        $header = $_POST['header'];
        $body = $_POST['body'];
        
        $content = "Header:\n\n" . $header . "\n\n" . "Body\n\n" . $body;

        // Define the filename
        $filename = "exported_content.txt";
        
        // Write content to the file
        if (file_put_contents($filename, $content)) {
            echo "Content successfully exported to <a href='$filename'>$filename</a>";
        } else {
            echo "Failed to write content to file.";
        }
    } else {
        echo "No content provided.";
    }
} else {
    echo "Invalid request method.";
}
?>