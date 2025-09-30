<?php
// /cart_add.php
session_start();
require __DIR__ . '/../functions/db.php';

// Read POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$qty        = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

if ($product_id <= 0) {
  header('Location: ../pages/products.php'); // fallback
  exit;
}

// Make sure the product exists (optional but good)
$stmt = $conn->prepare("SELECT product_id FROM products WHERE product_id = ? LIMIT 1");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$exists) {
  header('Location: ../pages/products.php');
  exit;
}

// Cart structure in session: [ product_id => qty, ... ]
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + $qty;

// Go to cart page
header('Location: ../pages/cart.php');
exit;
