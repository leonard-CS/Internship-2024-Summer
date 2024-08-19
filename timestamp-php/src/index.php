<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Post Management</title>
</head>
<body>
    <h1>Post Management</h1>
    <form action="submit_post.php" method="POST">
        <h2>Title</h2>
        <textarea name="title" rows="1" cols="100" placeholder="Enter your title here..."></textarea>
        <h2>Content</h2>
        <textarea name="content" rows="10" cols="100" placeholder="Enter your content here..."></textarea>
        <br>
        <button type="submit">Submit Post</button>
    </form>

    <h2>All Posts</h2>
    <?php include 'display_posts.php'; ?>
</body>
</html>
