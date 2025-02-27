<?php
session_start();
require '../database/db.php';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    
    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
                $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => (isset($_SESSION['cart'][$product_id]) ? 
                         $_SESSION['cart'][$product_id]['quantity'] + $quantity : 
                         $quantity)
        ];
                header('Location: cart.php');
        exit();
    }
}
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #ff5a00;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            margin: 0;
            color: #fff;
        }

        nav {
            margin-left: auto;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            margin: 0;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }
        main {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        h1, h2 {
            color: #ff5a00;
        }

        #product-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }

        #product-details img {
            width: 400px;
            height: 400px;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border-radius: 8px;
        }

        #product-details h3 {
            margin: 0 0 10px;
        }

        #product-details p {
            margin: 0 0 10px;
        }

        .add-to-cart-form {
            display: flex;
            flex-direction: column;
        }

        .add-to-cart-form label {
            margin-bottom: 5px;
        }

        .add-to-cart-form input[type="number"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add-to-cart-form button {
            padding: 10px;
            background-color: #ff5a00;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-to-cart-form button:hover {
            background-color: #e64a00;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>
<header>
    <h1 class="header-title">Product Details</h1>
    <nav>
        <ul>
            <li><a href="product.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
    <section id="product-details">
        <?php if ($product): ?>
            <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Price: Rs<?php echo htmlspecialchars($product['price']); ?></p>
            <form class="add-to-cart-form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $product_id; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required>
                <button type="submit">Add to Cart</button>
            </form>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Online Shopping Store. All rights reserved.</p>
</footer>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>