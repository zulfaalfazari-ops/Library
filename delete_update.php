<?php
include 'config.php';

$message = '';
$message_type = '';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .navbar-nav .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #f39c12;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- ===== NAVBAR (مطابق لـ index.html مع إضافة روابط صفحاتك) ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.html">
            <img src="logo.png" alt="BookSphere Logo" width="45" height="45" class="rounded-circle me-2">
            BookSphere Library
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="bookcatalog.html">Catalog</a></li>
                <li class="nav-item"><a class="nav-link" href="reservation.html">Reservation</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.html">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="myaccount.html">My Account</a></li>
                <li class="nav-item"><a class="nav-link" href="questionnaire.html">Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="calculate.html">Calculator</a></li>
                <li class="nav-item"><a class="nav-link" href="funpage.html">Puzzle</a></li>
                <li class="nav-item"><a class="nav-link" href="search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="insert.php">Insert</a></li>
                <li class="nav-item"><a class="nav-link active" href="delete_update.php">Delete/Update</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white"><h3 class="mb-0">🗑️ Delete / ✏️ Update Books</h3></div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>
                    <?php if (count($books_list) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark"><tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Available</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php foreach ($books_list as $book): ?>
                                    <tr>
                                        <td><?php echo $book['book_id']; ?></td>
                                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                                        <td><?php echo $book['available']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $book['book_id']; ?>">✏️ Edit</button>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                                <input type="hidden" name="delete_id" value="<?php echo $book['book_id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                                            </form>
                                            <!-- Modal -->
                                            <div class="modal fade" id="editModal<?php echo $book['book_id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST">
                                                            <div class="modal-header bg-warning"><h5 class="modal-title">✏️ Edit Book</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="update_id" value="<?php echo $book['book_id']; ?>">
                                                                <div class="mb-2"><label>Title</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required></div>
                                                                <div class="mb-2"><label>Author</label><input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required></div>
                                                                <div class="mb-2"><label>Category</label><input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($book['category']); ?>"></div>
                                                                <div class="mb-2"><label>Available Copies</label><input type="number" name="available" class="form-control" value="<?php echo $book['available']; ?>"></div>
                                                                <div class="mb-2"><label>Image filename</label><input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($book['image']); ?>"></div>
                                                            </div>
                                                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Changes</button></div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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
</main>

<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4"><h4 class="text-warning">BookSphere Library</h4><p class="text-secondary">Your gateway to knowledge</p></div>
            <div class="col-md-4 mb-4"><h4 class="text-warning">Quick Links</h4><ul class="list-unstyled"><li><a href="about.html" class="text-secondary text-decoration-none">About Us</a></li><li><a href="contact.html" class="text-secondary text-decoration-none">Contact</a></li><li><a href="questionnaire.html" class="text-secondary text-decoration-none">Give Feedback</a></li><li><a href="calculate.html" class="text-secondary text-decoration-none">Fine Calculator</a></li><li><a href="funpage.html" class="text-secondary text-decoration-none">Puzzle Game</a></li></ul></div>
            <div class="col-md-4 mb-4"><h4 class="text-warning">Contact</h4><p class="text-secondary">Email: info@booksphere.edu.om</p><p class="text-secondary">Phone: +968 1234 5678</p></div>
        </div>
        <div class="text-center pt-3 border-top border-secondary"><p class="text-secondary">&copy; 2026 BookSphere Library. All rights reserved.</p></div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>