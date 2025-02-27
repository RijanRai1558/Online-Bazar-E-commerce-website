<?php
session_start();
require '../database/db.php';
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}
if ($search_query) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY RAND()");
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("s", $search_param);
} else {
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY RAND()");
}
$stmt->execute();
$result = $stmt->get_result();
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

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }
    $stmt->close();
    header('Location: product.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
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
    flex-grow: 1;
    padding: 20px;
}

h1, h2 {
    color: #333;
}

#products-section {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}

.search-form {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

#products {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    justify-content: center;
    margin-top: 10px;
}

.no-results {
    font-size: 18px;
    color: #ff5a00;
    font-weight: bold;
    text-align: center;
    margin-top: 20px;
}

.product-item {
        background-color: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
    }

    .product-item a {
        text-decoration: none;
        color: inherit;
    }

    .product-item img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 5px;
        margin: 0 auto;
    }
@media (max-width: 1200px) {
    #products {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 992px) {
    #products {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    #products {
        grid-template-columns: repeat(2, 1fr); 
    }
}

@media (max-width: 480px) {
    #products {
        grid-template-columns: repeat(1, 1fr); 
    }
}

.product-item h3 {
    font-size: 16px;
    margin: 10px 0;
    color: #333;
}

.product-item p {
    font-size: 14px;
    color: #666;
}

.product-item p.price {
    font-size: 18px;
    font-weight: bold;
    color: #ff5a00;
}

.add-to-cart-form {
    display: flex;
    flex-direction: column;
    margin-top: 10px;
}

.add-to-cart-form input {
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.add-to-cart-form button {
    padding: 10px;
    background-color: #ff5a00;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.add-to-cart-form button:hover {
    background-color: #e64a00;
}

.search-form {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.search-form input {
    padding: 10px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.search-form button {
    padding: 10px 15px;
    background-color: #ff5a00;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.search-form button:hover {
    background-color: #e64a00;
}

footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 15px 0;
    position: relative;
}

    </style>
</head>
<body>
<header>
    <h1>Products</h1>
    <nav>
        <ul>
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
    <section id="products-section">
        <h2>Product List</h2>
        <div class="search-form">
            <form method="GET" action="product.php">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div id="products">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="product-item">
                        <a href="product_details.php?id=<?php echo $product['id']; ?>">
                            <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            
                            <p class="price">Price: Rs<?php echo htmlspecialchars($product['price']); ?></p>
                        </a>
                        <form class="add-to-cart-form" method="POST" action="product.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" required>
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-results">No products found.</p>
            <?php endif; ?>
        </div>
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