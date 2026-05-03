<?php
require_once 'config.php';

// ========== STEP 1: CLASS FOR SINGLE RECORD ==========
class UserRecord {
    private $user_id;
    private $name;
    private $email;
    
    public function __construct($user_id, $name, $email) {
        $this->user_id = (int)$user_id;
        $this->name = htmlspecialchars($name);
        $this->email = htmlspecialchars($email);
    }
    
    public function getUserId() { return $this->user_id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
}

// ========== STEP 2: PROCESS REGISTRATION ==========
$name = $_POST['firstname'] . ' ' . $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$university_id = $_POST['university_id'];

$errors = [];

if (empty($_POST['firstname'])) $errors[] = "First name is required";
if (empty($_POST['lastname'])) $errors[] = "Last name is required";
if (empty($email)) $errors[] = "Email is required";
if (empty($password)) $errors[] = "Password is required";
if ($password !== $confirm_password) $errors[] = "Passwords do not match";
if (!isset($_POST['terms'])) $errors[] = "You must agree to Terms";

$registration_success = false;

if (empty($errors)) {
    // Check if email exists
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "Email already registered";
    } else {
        // Insert new user (without password column - will need to add it)
        $insert_sql = "INSERT INTO users (name, email) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "ss", $name, $email);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $registration_success = true;
        } else {
            $errors[] = "Registration failed";
        }
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($check_stmt);
}

// ========== STEP 3: ARRAY OF OBJECTS (ALL USERS) ==========
$users_array = [];
$sql = "SELECT user_id, name, email FROM users ORDER BY user_id";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $users_array[] = new UserRecord($row['user_id'], $row['name'], $row['email']);
}

mysqli_close($conn);

// ========== STEP 4: FUNCTION TO DISPLAY ARRAY OF OBJECTS ==========
function displayUserRecords($users) {
    if (empty($users)) return "<p>No users found.</p>";
    
    $html = "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $html .= "<thead><tr style='background-color: #007bff; color: white;'>";
    $html .= "<th>#</th><th>User ID</th><th>Full Name</th><th>Email</th>";
    $html .= "</tr></thead><tbody>";
    
    $counter = 1;
    foreach ($users as $user) {
        $html .= "<tr>";
        $html .= "<td style='text-align: center;'>" . $counter++ . "</td>";
        $html .= "<td style='text-align: center;'>" . $user->getUserId() . "</td>";
        $html .= "<td>" . $user->getName() . "</td>";
        $html .= "<td>" . $user->getEmail() . "</td>";
        $html .= "</tr>";
    }
    
    $html .= "</tbody></table>";
    return $html;
}

// ========== STEP 5: XHTML OUTPUT ==========
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Registration - BookSphere Library</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { background: #d4edda; padding: 15px; border-left: 5px solid #28a745; margin: 20px 0; }
        .error { background: #f8d7da; padding: 15px; border-left: 5px solid #dc3545; margin: 20px 0; }
        table { margin-top: 20px; }
        th, td { padding: 8px; text-align: left; }
        tr:nth-child(even) { background: #f2f2f2; }
        .back-link { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Registration Result</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Registration Failed:</strong>
                <ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
            </div>
            <a href="register.html" class="back-link">← Go Back</a>
        <?php elseif ($registration_success): ?>
            <div class="success">
                <strong>✓ Welcome, <?php echo htmlspecialchars($name); ?>!</strong><br />
                Your account has been created successfully.
            </div>
            
            <h2>📊 All Library Members (Array of UserRecord Objects)</h2>
            <p><strong>Total Members:</strong> <?php echo count($users_array); ?></p>
            
            <?php echo displayUserRecords($users_array); ?>
            
            <p><em>* This table was generated by iterating over an <strong>array of UserRecord objects</strong> using <strong>displayUserRecords()</strong> function.</em></p>
            
            <a href="login.html" class="back-link">→ Proceed to Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
