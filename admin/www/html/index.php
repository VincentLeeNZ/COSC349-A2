
<?php
include "../inc/dbinfo.inc";
session_start();

if (!isset($_SESSION["username"])) {
    header("location:login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #35424a;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #35424a;
            color: white;
            text-align: center;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #35424a;
            color: white;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #35424a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #29373d;
        }
    </style>
</head>
<body>

<header>
    <h1>Admin Home Page</h1>
    <p>Welcome, <?php echo $_SESSION["username"]; ?></p>
</header>

<nav>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Showing contents of products table:</h2>

    <table>
        <tr>
            <th>Product code</th>
            <th>Product name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th> 
        </tr>

        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
        
$database = mysqli_select_db($connection, DB_DATABASE);
        
        
$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE;
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        

$q = $pdo->query("SELECT * FROM products");
while ($row = $q->fetch()) {
    echo "<tr>";
    echo "<td>" . $row["code"] . "</td>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["price"] . "</td>";
    echo "<td>" . $row["description"] . "</td>";
    // Display the image in a table cell
    echo '<td><img src="' . $row["s3"] . '" alt="Product Image" style="max-width: 100px;"></td>';
    echo '<td><form method="post"><input type="hidden" name="product_code" value="' . $row["code"] . '"><input type="submit" name="remove_product" value="Remove"></form></td>';
    echo "</tr>\n";
}
        ?>

    </table>

    <h2>Add a New Product:</h2>
<form method="post">
    <label for="code">Product Code:</label>
    <input type="text" id="code" name="code" required><br>

    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="price">Price:</label>
    <input type="text" id="price" name="price" required><br>

    <label for="description">Description:</label>
    <input type="text" id="description" name="description" required><br>

    <label for="image_url">Image URL:</label>
    <input type="text" id="image_url" name="image_url" required><br>

    <input type="submit" value="Add Product">
</form>

    <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["remove_product"])) {
        $code = $_POST["product_code"];
    
        $delete_query = "DELETE FROM products WHERE code = :code";
        $delete_statement = $pdo->prepare($delete_query);
        $delete_statement->bindParam(":code", $code);

        if ($delete_statement->execute()) {
            echo "<p>Product removed successfully!</p>";
        }
    } else {
        $code = $_POST["code"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $description = $_POST["description"];
        $imageURL = $_POST["image_url"]; // Get the image URL
        
        // Insert the image URL into the "s3" column in the database along with other product information
        $insert_query = "INSERT INTO products (code, name, price, description, s3) VALUES (:code, :name, :price, :description, :s3)";
        $insert_statement = $pdo->prepare($insert_query);
        $insert_statement->bindParam(":code", $code);
        $insert_statement->bindParam(":name", $name);
        $insert_statement->bindParam(":price", $price);
        $insert_statement->bindParam(":description", $description);
        $insert_statement->bindParam(":s3", $imageURL); // Bind the image URL to the "s3" column
        
        if ($insert_statement->execute()) {
            echo "<p>New product added successfully!</p>";
        }
    }
}
    
