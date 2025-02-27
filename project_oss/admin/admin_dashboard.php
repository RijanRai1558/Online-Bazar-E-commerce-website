<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}
require '../database/db.php';
$images_dir = '../images/';
if (!is_dir($images_dir)) {
    mkdir($images_dir, 0777, true);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];
            $target = $images_dir . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssds", $name, $description, $price, $image);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Failed to upload image.";
            }
        } elseif ($_POST['action'] === 'update') {
            $product_id = $_POST['product_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];
            $target = $images_dir . basename($image);

            if ($image) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
                    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $product_id);
                } else {
                    echo "Failed to upload image.";
                }
            } else {
                $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
                $stmt->bind_param("ssdi", $name, $description, $price, $product_id);
            }

            if (isset($stmt)) {
                $stmt->execute();
                $stmt->close();
            }
        } elseif ($_POST['action'] === 'delete_product') {
            $product_id = $_POST['product_id'];
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'delete_admin') {
            $admin_username = $_POST['admin_username'];
            $stmt = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'admin'");
            $stmt->bind_param("s", $admin_username);
            $stmt->execute();
            $stmt->close();
        }
    }
}
$query = $conn->prepare("SELECT * FROM products");
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ff5a00;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        nav ul li a:hover {
            text-decoration: underline;
        }
        main {
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        #admin-dashboard {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            padding: 10px;
            background-color: #ff5a00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        button:hover {
            background-color: #e64a00;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
            border-radius: 5px;
        }
        .product-item-details {
            flex-grow: 1;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            position: relative;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="admin_index.php">Home</a></li>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_orders.php">View Orders</a></li>
            <li><a href="admin_change_credentials.php">Change Credentials</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="admin-dashboard">
        <h2>Manage Products</h2>
        <h3>Add New Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" required>
            <button type="submit">Add Product</button>
        </form>
        <h3>Existing Products</h3>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <li class="product-item">
                        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-item-details">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <p>Price: Rs<?php echo htmlspecialchars($product['price']); ?></p>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                <label for="price">Price:</label>
                                <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                                <label for="image">Image:</label>
                                <input type="file" id="image" name="image">
                                <button type="submit">Update Product</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit">Delete Product</button>
                            </form>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
        <h3>Delete Admin Credentials</h3>
        <form method="POST">
            <input type="hidden" name="action" value="delete_admin">
            <label for="admin_username">Admin Username:</label>
            <input type="text" id="admin_username" name="admin_username" required>
            <button type="submit">Delete Admin</button>
        </form>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Online Shopping Store. All rights reserved.</p>
</footer>
</body>
</html>

<?php
$query->close();
$conn->close();
?>