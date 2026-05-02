<?php
include 'config.php';

$search_results = [];
$search_term = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_term = trim($_POST['search_term']);
    $search_term = mysqli_real_escape_string($conn, $search_term);
    
    $sql = "SELECT * FROM books WHERE title LIKE '%$search_term%' OR author LIKE '%$search_term%' OR category LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search - BookSphere</title>
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
                <li class="nav-item"><a class="nav-link active" href="search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="insert.php">Insert</a></li>
                <li class="nav-item"><a class="nav-link" href="delete_update.php">Delete/Update</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">🔍 Search Books</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="input-group">
                            <input type="text" name="search_term" class="form-control" placeholder="Search by title, author, or category..." value="<?php echo htmlspecialchars($search_term); ?>" required>
                            <button type="submit" name="search" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                        <div class="mt-4">
                            <h5>Search Results for: "<?php echo htmlspecialchars($search_term); ?>"</h5>
                            <?php if (count($search_results) > 0): ?>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark"><tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Available</th><th>Image</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($search_results as $book) { ?>
                                            <tr>
                                                <td><?php echo $book['book_id']; ?></td>
                                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                                <td><?php echo htmlspecialchars($book['category']); ?></td>
                                                <td><?php if ($book['available'] > 0): ?><span class="badge bg-success"><?php echo $book['available']; ?> Available</span><?php else: ?><span class="badge bg-danger">Not Available</span><?php endif; ?></td>
                                                <td><?php if ($book['image']): ?><img src="<?php echo $book['image']; ?>" width="50" height="60"><?php else: ?>No image<?php endif; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mt-3">No books found matching your search.</div>
                            <?php endif; ?>
                        </div>
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