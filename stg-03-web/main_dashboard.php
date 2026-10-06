<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aura Health - IT Support Dashboard</title>
    <style>
        body { font-family: Arial; background-color: #f4f4f4; text-align: center; padding-top: 50px; }
        .dashboard-box { background: white; padding: 30px; width: 500px; margin: 0 auto; border-radius: 5px; box-shadow: 0px 0px 10px #aaa; }
        .btn-logs { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #e74c3c; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;}
    </style>
</head>
<body>
    <div class="dashboard-box">
        <h2>Welcome, IT Support</h2>
        <p>Your access level is: <strong>Standard</strong></p>
        <p>System monitoring is active. If you need to review internal server events, please access the restricted logs below.</p>
        
        <a href="secondary_login.php" class="btn-logs">Check Logs (Admin Only)</a>
    </div>
</body>
</html>