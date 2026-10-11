<?php
session_start();

// Initialize the SQLite database and store credentials dynamically
$db = new SQLite3('/tmp/legacy_portal.db');
$db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, password TEXT)");
$db->exec("CREATE TABLE IF NOT EXISTS superadmins (id INTEGER PRIMARY KEY, username TEXT, password TEXT)");

// Insert the standard user (found in Stage 2)
$db->exec("DELETE FROM users");
$db->exec("INSERT INTO users (id, username, password) VALUES (1, 'it_support', 'A_@2u0r5@_2!0@%')");

// Insert the Admin user for the secondary login
$db->exec("DELETE FROM superadmins");
$db->exec("INSERT INTO superadmins (id, username, password) VALUES (1, 'Admin', 'SUPER_SECRET_UNGUESSABLE_PASSWORD')");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // SECURE QUERY: Protects the first login against SQL Injection
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
    $stmt->bindValue(':username', $user, SQLITE3_TEXT);
    $stmt->bindValue(':password', $pass, SQLITE3_TEXT);
    
    $result = $stmt->execute();
    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $_SESSION['authenticated'] = true;
        header("Location: main_dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aura Health - Legacy Portal</title>
    <style>
        body { font-family: Arial; background-color: #f4f4f4; text-align: center; padding-top: 100px; }
        .login-box { background: white; padding: 20px; width: 300px; margin: 0 auto; border-radius: 5px; box-shadow: 0px 0px 10px #aaa; }
        input { width: 90%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #0056b3; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Aura Health Staging</h2>
        <p>Authorized Personnel Only</p>
        <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>