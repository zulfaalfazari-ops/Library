<?php
include 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $available = intval($_POST['available']);
    $image = trim($_POST['image']);
    
    if (empty($title) || empty($author)) {
        $message = "Title and Author are required!";
        $message_type = "danger";
    } else {
        $title = mysqli_real_escape_string($conn, $title);
        $author = mysqli_real_escape_string($conn, $author);
        $category = mysqli_real_escape_string($conn, $category);
        $image = mysqli_real_escape_string($conn, $image);
        
        $sql = "INSERT INTO books (title, author, category, available, image) VALUES ('$title', '$author', '$category', $available, '$image')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Book added successfully!";
            $message_type = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $message_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert - BookSphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">📚 BookSphere</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
            <li class="nav-item"><a class="nav-link" href="insert.php">Insert</a></li>
            <li class="nav-item"><a class="nav-link" href="delete_update.php">Delete/Update</a></li>
        </ul>
    </div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">➕ Insert New Book</h3>
                </div>
                <div class="card-body">
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Book Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Author *</label>
                            <input type="text" name="author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" placeholder="e.g., Programming, Literature, Science">
                        </div>
                        <div class="mb-3">
                            <label>Available Copies</label>
                            <input type="number" name="available" class="form-control" value="1" min="0">
                        </div>
                        <div class="mb-3">
                            <label>Image filename</label>
                            <input type="text" name="image" class="form-control" placeholder="e.g., book.jpg">
                        </div>
                        <button type="submit" name="insert" class="btn btn-success w-100">Insert Book</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2026 BookSphere Library. All rights reserved.</p>
</footer>

</body>
</html>