<?php
session_start();
if (!isset($_SESSION['superadmin'])) {
    die("HTTP 403 Forbidden: You must bypass the admin login to view this page.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aura Health - Internal Logs</title>
    <style>
        body { font-family: monospace; background-color: #1e1e1e; color: #00ff00; padding: 50px; }
        .container { border: 1px solid #00ff00; padding: 20px; }
        a { color: #00ff00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>ACCESS GRANTED: Internal Server Logs</h1>
        <p>System Status: COMPROMISED</p>
        <p>Viewing restricted access logs...</p>
        
        <p><em>Note: The flag is not displayed on the screen. Inspect the page elements.</em></p>

        <!-- Congratulations on bypassing the SQL auth! -->
        <!-- Stage 3 Flag: Vital{SQL_1nj3ct10n_m4st3r} -->

        <hr>
        <h2>Next Steps (Stage 4):</h2>
        <p>Network logs indicate an encrypted SSH key was exfiltrated from this server.</p>
        <p>Download the captured network traffic here: <br>
           <a href="http://localhost:8081/capture.pcap" target="_blank">Download capture.pcap</a>
        </p>
    </div>
</body>
</html>