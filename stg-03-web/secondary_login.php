<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header("Location: index.php");
    exit();
}

$db = new SQLite3('/tmp/legacy_portal.db');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // INTENTIONALLY VULNERABLE QUERY: This allows SQL Injection
    $query = "SELECT * FROM superadmins WHERE username = '$user' AND password = '$pass'";
    
    // The @ suppresses raw PHP errors so attackers have to guess blindly or use standard SQLi logic
    $result = @$db->querySingle($query, true);

    if ($result) {
        $_SESSION['superadmin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Access Denied: Invalid Admin Credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aura Health - Restricted Area</title>
    <style>
        body { font-family: Arial; background-color: #2c3e50; color: white; text-align: center; padding-top: 100px; }
        .login-box { background: #34495e; padding: 20px; width: 350px; margin: 0 auto; border-radius: 5px; border: 2px solid #e74c3c; }
        input { width: 90%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Internal Logs Access</h2>
        <p>WARNING: Admin Authentication Required</p>
        <?php if($error) echo "<p style='color:#ffcccc;'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Admin Password" required>
            <button type="submit">Access Logs</button>
        </form>
    </div>
</body>
</html>