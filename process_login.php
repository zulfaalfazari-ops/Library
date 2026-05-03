<?php
require_once 'config.php';

$email = $_POST['loginEmail'] ?? '';
$password = $_POST['loginPassword'] ?? '';

$errors = [];
$login_success = false;
$user_data = null;

if (empty($email)) $errors[] = "Email is required";
if (empty($password)) $errors[] = "Password is required";

if (empty($errors)) {
    // Query user by email
    $sql = "SELECT user_id, name, email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        // For demo: any non-empty password works (since no password column)
        $login_success = true;
    } else {
        $errors[] = "Email not found. Please register first.";
    }
    mysqli_stmt_close($stmt);
}

// Get books from database
$books_sql = "SELECT book_id, title, author, category, available FROM books";
$books_result = mysqli_query($conn, $books_sql);

// Get borrowing statistics
$stats_sql = "SELECT 
                COUNT(*) as total_borrows,
                SUM(CASE WHEN status = 'Borrowed' THEN 1 ELSE 0 END) as active_borrows,
                SUM(CASE WHEN status = 'Overdue' THEN 1 ELSE 0 END) as overdue_borrows
              FROM borrow";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

// Get recent activity with JOIN
$recent_sql = "SELECT b.borrow_id, u.name as user_name, bk.title as book_title, 
                      b.borrow_date, b.status
               FROM borrow b
               JOIN users u ON b.user_id = u.user_id
               JOIN books bk ON b.book_id = bk.book_id
               ORDER BY b.borrow_date DESC
               LIMIT 5";
$recent_result = mysqli_query($conn, $recent_sql);

mysqli_close($conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Login Result - BookSphere Library</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { background: #d4edda; padding: 15px; border-left: 5px solid #28a745; margin: 20px 0; }
        .error { background: #f8d7da; padding: 15px; border-left: 5px solid #dc3545; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .stats-box { display: inline-block; width: 30%; margin: 10px; padding: 15px; background: #e7f3ff; border-radius: 8px; text-align: center; }
        .back-link { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Login Result</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Login Failed:</strong>
                <ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
            </div>
            <a href="login.html" class="back-link">← Try Again</a>
            <a href="register.html" class="back-link">→ Register</a>
        <?php elseif ($login_success): ?>
            <div class="success">
                <strong>✓ Welcome back, <?php echo htmlspecialchars($user_data['name']); ?>!</strong>
            </div>
            
            <h2>📋 Your Account</h2>
            <table>
                <tr><th style="width:30%">Field</th><th>Value</th></tr>
                <tr><td>User ID</td><td><?php echo $user_data['user_id']; ?></td></tr>
                <tr><td>Name</td><td><?php echo htmlspecialchars($user_data['name']); ?></td></tr>
                <tr><td>Email</td><td><?php echo htmlspecialchars($user_data['email']); ?></td></tr>
            </table>
            
            <h2>📊 Library Statistics</h2>
            <div style="text-align:center">
                <div class="stats-box"><h3><?php echo $stats['total_borrows']; ?></h3><p>Total Borrows</p></div>
                <div class="stats-box"><h3><?php echo $stats['active_borrows']; ?></h3><p>Active Borrows</p></div>
                <div class="stats-box"><h3><?php echo $stats['overdue_borrows']; ?></h3><p>Overdue Books</p></div>
            </div>
            
            <h2>📚 Available Books</h2>
            <table>
                <thead><tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Available</th></tr></thead>
                <tbody>
                    <?php while($book = mysqli_fetch_assoc($books_result)): ?>
                    <tr>
                        <td><?php echo $book['book_id']; ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                        <td><?php echo $book['available']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <h2>🔄 Recent Borrowing Activity (JOIN Query)</h2>
            <table>
                <thead><tr><th>Borrow ID</th><th>User</th><th>Book</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php while($activity = mysqli_fetch_assoc($recent_result)): ?>
                    <tr>
                        <td><?php echo $activity['borrow_id']; ?></td>
                        <td><?php echo htmlspecialchars($activity['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($activity['book_title']); ?></td>
                        <td><?php echo $activity['borrow_date']; ?></td>
                        <td style="color: <?php echo $activity['status'] == 'Overdue' ? 'red' : 'green'; ?>">
                            <?php echo $activity['status']; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <a href="index.html" class="back-link">← Home</a>
        <?php endif; ?>
    </div>
</body>
</html>
