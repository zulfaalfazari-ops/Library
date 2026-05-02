<?php
include 'config.php';

$message = '';
$message_type = '';
$edit_book = null;

// Handle Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM books WHERE book_id = $delete_id";
    
    if (mysqli_query($conn, $sql)) {
        $message = "Book deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "danger";
    }
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $update_id = intval($_POST['update_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $available = intval($_POST['available']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    
    $sql = "UPDATE books SET title='$title', author='$author', category='$category', available=$available, image='$image' WHERE book_id=$update_id";
    
    if (mysqli_query($conn, $sql)) {
        $message = "Book updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($conn);
        $message_type = "danger";
    }
}

// Get all books to display
$result = mysqli_query($conn, "SELECT * FROM books ORDER BY book_id DESC");
$books_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete/Update - BookSphere</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #f39c12 !important;
            border-bottom: 2px solid #f39c12;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.html">
            <img src="logo.png" alt="BookSphere Logo" width="45" height="45" class="rounded-circle me-2">
            BookSphere
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                <li class="nav-item"><a class="nav-link" href="catalog.html">Catalog</a></li>
                <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.html">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="myaccount.html">My Account</a></li>
                <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="insert.php">Insert</a></li>
                <li class="nav-item"><a class="nav-link active" href="delete_update.php">Delete/Update</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">🗑️ Delete / ✏️ Update Books</h3>
                </div>
                <div class="card-body">
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if (count($books_list) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Available</th><th>Actions</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books_list as $book): ?>
                                    <tr>
                                        <td><?php echo $book['book_id']; ?></td>
                                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                                        <td><?php echo $book['available']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $book['book_id']; ?>">
                                                ✏️ Edit
                                            </button>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                                <input type="hidden" name="delete_id" value="<?php echo $book['book_id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                                            </form>
                                            
                                            <div class="modal fade" id="editModal<?php echo $book['book_id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title">✏️ Edit Book</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="update_id" value="<?php echo $book['book_id']; ?>">
                                                                <div class="mb-2"><label>Title</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required></div>
                                                                <div class="mb-2"><label>Author</label><input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required></div>
                                                                <div class="mb-2"><label>Category</label><input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($book['category']); ?>"></div>
                                                                <div class="mb-2"><label>Available Copies</label><input type="number" name="available" class="form-control" value="<?php echo $book['available']; ?>"></div>
                                                                <div class="mb-2"><label>Image filename</label><input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($book['image']); ?>"></div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">No books found in the database.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2026 BookSphere Library. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>