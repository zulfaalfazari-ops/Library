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
                <li class="nav-item"><a class="nav-link active" href="insert.php">Insert</a></li>
                <li class="nav-item"><a class="nav-link" href="delete_update.php">Delete/Update</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white"><h3 class="mb-0">➕ Insert New Book</h3></div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3"><label>Book Title *</label><input type="text" name="title" class="form-control" required></div>
                        <div class="mb-3"><label>Author *</label><input type="text" name="author" class="form-control" required></div>
                        <div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control" placeholder="e.g., Programming, Literature, Science"></div>
                        <div class="mb-3"><label>Available Copies</label><input type="number" name="available" class="form-control" value="1" min="0"></div>
                        <div class="mb-3"><label>Image filename</label><input type="text" name="image" class="form-control" placeholder="e.g., book.jpg"></div>
                        <button type="submit" name="insert" class="btn btn-success w-100">Insert Book</button>
                    </form>
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