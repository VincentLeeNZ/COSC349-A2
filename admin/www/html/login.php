<?php
include "../inc/dbinfo.inc";

session_start();

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);


$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE;
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);



try {
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM login WHERE username=:username AND password=:password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            if ($row["usertype"] == "user") {
                $_SESSION["username"] = $username;
                header("location: home-page.php");
                exit();
            } elseif ($row["usertype"] == "admin") {
                $_SESSION["username"] = $username;
                header("location: index.php");
                exit();
            }
        } else {
            echo "Username or password incorrect";
        }
    } else {
        echo "Error executing query";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <center>
        <h1>Login Form</h1>
        <br><br><br><br>
        <div style="background-color: grey; width: 500px;">
            <br><br>
            <form action="#" method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <br><br>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <br><br>
                <div>
                    <input type="submit" value="Login">
                </div>
            </form>
            <br><br>
        </div>
    </center>
</body>
</html>
