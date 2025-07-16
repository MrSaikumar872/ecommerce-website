<?php
include '../includes/db.php';
session_start();

// Redirect if not admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch product data to edit
if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product from DB
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle update form submission
if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // Handle image upload (optional)
    $image = $product['image']; // default image
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $newImage = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $newImage;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $newImage;
        }
    }

    // Update query
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, description=? WHERE id=?");
    $stmt->execute([$name, $price, $image, $description, $product_id]);

    // Redirect after update
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f7fa;
        }
        .container {
            width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px gray;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
        button {
            background-color: green;
            color: white;
            padding: 10px;
            border: none;
            margin-top: 15px;
            width: 100%;
            cursor: pointer;
        }
        img {
            width: 100px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

            <label>Description:</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

            <label>Current Image:</label><br>
            <img src="../uploads/<?= $product['image'] ?>" alt="Product Image"><br>

            <label>Change Image:</label>
            <input type="file" name="image">

            <button type="submit" name="update">Update Product</button>
        </form>
    </div>
</body>
</html>
