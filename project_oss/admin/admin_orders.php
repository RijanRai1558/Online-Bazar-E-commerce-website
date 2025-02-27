<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}
require '../database/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $order_id = $_POST['order_id'];
        if ($_POST['action'] === 'cancel') {
            $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'mark_successful') {
            $stmt = $conn->prepare("UPDATE orders SET status = 'successful' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
            $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}
$query = $conn->prepare("SELECT orders.id, orders.total_amount, orders.created_at, orders.status, users.username FROM orders JOIN users ON orders.user_id = users.id");
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; 
            min-height: 100vh; 
        }

        header {
            background-color: #ff5a00;
            color: #333;
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
            flex: 1; 
        }

        #admin-orders {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item-details {
            flex-grow: 1;
        }

        .order-item-actions {
            text-align: right;
        }

        .order-item-actions form {
            display: inline-block;
        }

        .order-item-actions button {
            padding: 5px 10px;
            background-color: #ff5a00;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }

        .order-item-actions button:hover {
            background-color: #e64a00;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .product-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
            border-radius: 5px;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            width: 100%; 
        }
    </style>
</head>
<body>
<header>
    <h1>View Orders</h1>
    <nav>
        <ul>
            <li><a href="admin_index.php">Home</a></li>
            <li><a href="admin_products.php">Products</a></li>
            <li><a href="admin_change_credentials.php">Change Credentials</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="admin-orders">
        <h2>Order List</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <li class="order-item">
                        <div class="order-item-details">
                            <h3>User: <?php echo htmlspecialchars($order['username']); ?></h3>
                            <p>Total Amount: Rs<?php echo htmlspecialchars($order['total_amount']); ?></p>
                            <p>Order Date: <?php echo htmlspecialchars($order['created_at']); ?></p>
                            <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                            <h4>Products:</h4>
                            <ul>
                                <?php
                                $order_id = $order['id'];
                                $product_query = $conn->prepare("SELECT products.name, products.image, order_items.quantity FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?");
                                $product_query->bind_param("i", $order_id);
                                $product_query->execute();
                                $product_result = $product_query->get_result();
                                while ($product = $product_result->fetch_assoc()): ?>
                                    <li class="product-item">
                                        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        <div>
                                            <p><?php echo htmlspecialchars($product['name']); ?></p>
                                            <p>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></p>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                                <?php $product_query->close(); ?>
                            </ul>
                        </div>
                        <div class="order-item-actions">
                            <form method="POST" action="admin_orders.php">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" name="action" value="cancel">Cancel Order</button>
                                <button type="submit" name="action" value="mark_successful">Mark as Successful</button>
                                <button type="submit" name="action" value="delete">Delete Order</button>
                            </form>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Online Shopping Store. All rights reserved.</p>
</footer>
</body>
</html>