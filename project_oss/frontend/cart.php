<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}
require '../database/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #ff5a00;
            color: #333;
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin-left: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            margin-right: 20px;
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
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            flex-grow: 1;
        }

        #cart {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .cart-items-container { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }


        .cart-item {
            display: flex;
            flex-direction: column;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .cart-item-details {
            flex-grow: 1;
            font-size: 16px;
        }

        .cart-item-details h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #555;
        }

        .cart-item-actions {
            margin-top: 10px;
            text-align: right;
        }

        .cart-item-actions button {
            padding: 8px 15px;
            background-color: #ff5a00;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cart-item-actions button:hover {
            background-color: #555;
        }

        .checkout-button {
            display: block;
            width: 100%;
            padding: 12px 20px;
            background-color: #ff5a00;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 30px;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .checkout-button:hover {
            background-color: #555;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            bottom: 0;
        }

        .empty-cart-message {
            text-align: center;
            color: #888;
            font-size: 18px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<header>
    <h1>Cart</h1>
    <nav>
        <ul>
            <li><a href="product.php">Products</a></li>
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
    <section id="cart">
        <h2>Your Cart</h2>
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-items-container">  <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                    <div class="cart-item">
                        <img src="../images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="cart-item-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Price: Rs<?php echo htmlspecialchars($item['price']); ?></p>
                            <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                        </div>
                        <div class="cart-item-actions">
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="submit" name="remove">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>  <form method="POST" action="checkout.php">
                <button type="submit" class="checkout-button">Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Online Shopping Store. All rights reserved.</p>
</footer>
</body>
</html>