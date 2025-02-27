<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['cart'])) {
        header("Location: cart.php");
        exit();
    }


    $user_id = $_SESSION['user_id'];
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total_amount);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $order_id, $product_id, $item['quantity'], $item['image']);
        $stmt->execute();
        $stmt->close();
    }

    unset($_SESSION['cart']);
    header("Location: product.php?order_id=$order_id");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
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
            color: #fff;
            padding: 10px 0;
            text-align: center;
            display: flex;
    justify-content: space-between; 
    align-items: center; 
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        nav ul li a:hover {
            text-decoration: underline;
        }
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        #content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h1, h2 {
            margin: 0 0 20px;
        }
        p {
            margin: 20px 0;
        }
        a {
            color: #ff5a00;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
<header>
    <h1>Checkout</h1>
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
    <section id="checkout">
        <h2>Order Summary</h2>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])):?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li>
                        <img src="../images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div>
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Price: Rs<?php echo htmlspecialchars($item['price']); ?></p>
                            <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form method="POST" action="checkout.php">
                <button type="submit">Place Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.  <a href="product.php">Continue Shopping</a></p>  </p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Online Shopping Store. All rights reserved.</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>