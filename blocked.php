<?php
session_start();
$name = $_SESSION['blocked_name'] ?? "Visitor";
?>

<!DOCTYPE html>
<html>
<head>
<title>Access Denied</title>

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:Arial;
    background:linear-gradient(180deg,#0b1957,#132a73);
}

.card{
    background:#ff4d4d;
    color:white;
    padding:40px;
    border-radius:16px;
    text-align:center;
    width:400px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

.card h1{
    margin-bottom:10px;
}

.btn{
    margin-top:20px;
    display:inline-block;
    padding:10px 20px;
    background:white;
    color:#ff4d4d;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}
</style>

</head>
<body>

<div class="card">

<h1>Access Denied</h1>

<p>
Sorry <b><?php echo htmlspecialchars($name); ?></b><br>
You are currently <b>blocked</b> from entering the NEU Library.
</p>

<p>Please contact the library administrator.</p>

<a class="btn" href="index.php">Back</a>

</div>

</body>
</html>