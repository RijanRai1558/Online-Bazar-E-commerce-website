<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}
require '../database/db.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $password);
$stmt->fetch();
$stmt->close();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_username, $new_password, $user_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['username'] = $new_username;
    header('Location: admin_change_credentials.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Admin Credentials</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        #content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h1, h2 {
            color: #333;
            margin: 0 0 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
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
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: relative;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
    <h1>Change Admin Credentials</h1>
    <nav>
        <ul>
            <li><a href="admin_index.php">Dashboard</a></li>
            <li><a href="admin_dashboard.php">Manage Products</a></li>
            <li><a href="admin_orders.php">View Orders</a></li>
            <li><a href="admin_change_credentials.php">Change Credentials</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="content">
        <h2>Update Your Credentials</h2>
        <form method="POST" action="admin_change_credentials.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Update Credentials</button>
        </form>
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