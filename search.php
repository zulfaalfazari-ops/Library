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
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Author</th>
                                                <th>Category</th>
                                                <th>Available</th>
                                                <th>Image</th>
                                            <tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($search_results as $book): ?>
                                            <tr>
                                                <td><?php echo $book['book_id']; ?></td>
                                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                                <td><?php echo htmlspecialchars($book['category']); ?></td>
                                                <td>
                                                    <?php if ($book['available'] > 0): ?>
                                                        <span class="badge bg-success"><?php echo $book['available']; ?> Available</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Not Available</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($book['image']): ?>
                                                        <img src="<?php echo $book['image']; ?>" width="50" height="60">
                                                    <?php else: ?>
                                                        No image
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
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
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2026 BookSphere Library. All rights reserved.</p>
</footer>

</body>
</html>
