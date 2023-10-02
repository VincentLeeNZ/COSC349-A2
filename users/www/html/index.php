<?php include "../inc/dbinfo.inc"; ?>
<html>

<body>
    <?php

/* Connect to MySQL and select the database. */
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);


$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE;
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);


$q = $pdo->query("SELECT * FROM products");

// Initialize the cart in session if not already present
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Handle adding products to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_code"])) {
    $productCode = $_POST["product_code"];
    
    // Fetch the product details by code
    $product_query = $pdo->prepare("SELECT * FROM products WHERE code = :code");
    $product_query->bindParam(":code", $productCode);
    $product_query->execute();
    $product = $product_query->fetch();

    if ($product) {
        $_SESSION["cart"][] = $product;
        echo "<p>Product '{$product["name"]}' added to cart.</p>";
    } else {
        echo "<p>Invalid product code.</p>";
    }
}
?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Page for Users</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                /* background-image: url('https://leevi154-a2-cosc349.s3.amazonaws.com/IMG_2429.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center; */
            }


            th {
                text-align: left;
            }

            table,
            th,
            td {
                border: 2px solid grey;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 0.2em;
            }

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

            .container {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            }

            .product {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
                background-color: #fff;
            }
        </style>
    </head>

    <body>
        <header>
            <h1>Sick Shop</h1>
        </header>

        <div class="container">
            <?php
    while ($row = $q->fetch()) {
        echo '<div class="product">';
        $imageSrc = $row["s3"];
        echo '<img src="' . $imageSrc . '" alt="Product Image">';

        echo '<h3>' . $row["name"] . '</h3>';
        echo '<p><strong>Product code:</strong> ' . $row["code"] . '</p>';
        echo '<p><strong>Price:</strong> ' . $row["price"] . '</p>';
        echo '<p><strong>Description:</strong> ' . $row["description"] . '</p>';
        echo '<form method="post">';
        echo '<input type="hidden" name="product_code" value="' . $row["code"] . '">';
        echo '<input type="submit" value="Add to Cart">';
        echo '</form>';
        echo '</div>';
    }
    ?>
        </div>



        <h2>Your Shopping Cart:</h2>
        <?php
    if (count($_SESSION["cart"]) > 0) {
        foreach ($_SESSION["cart"] as $cartItem) {
            echo '<div class="product">';
            echo '<h3>' . $cartItem["name"] . '</h3>';
            echo '<p><strong>Price:</strong> ' . $cartItem["price"] . '</p>';
            echo '<p><strong>Description:</strong> ' . $cartItem["description"] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>


        </div>
    </body>

    </html>