<?php
// /pages/order_place.php
session_start();
require_once __DIR__ . '/../functions/db.php';

// Require login at checkout
if (empty($_SESSION['user'])) {
  header('Location: ../pages/login.php?redirect=../pages/cart.php');
  exit;
}

$user_id = (int)$_SESSION['user']['id'];
$cart    = $_SESSION['cart'] ?? [];

if (!$cart || !is_array($cart)) {
  header('Location: ../pages/cart.php');
  exit;
}

// Fetch products in cart (to snapshot names/prices)
$ids = array_map('intval', array_keys($cart));
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT product_id, name, price FROM products WHERE product_id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
$subtotal = 0.00;

while ($row = $res->fetch_assoc()) {
  $pid = (int)$row['product_id'];
  $qty = max(1, (int)$cart[$pid]);
  $unit = (float)$row['price'];
  $line = $unit * $qty;

  $items[] = [
    'product_id' => $pid,
    'name'       => $row['name'],   // if you later add a name column to order_items
    'price'      => $unit,          // unit price snapshot
    'qty'        => $qty,
    'line_total' => $line
  ];
  $subtotal += $line;
}
$stmt->close();

if (!$items) {
  header('Location: ../pages/cart.php');
  exit;
}

// Totals (adjust if you add real shipping/tax)
$shipping = 0.00;
$tax      = 0.00;
$total    = $subtotal + $shipping + $tax;

$conn->begin_transaction();

try {
  // Create order
  $stmt = $conn->prepare("INSERT INTO orders (user_id, subtotal, shipping, tax, total) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("idddd", $user_id, $subtotal, $shipping, $tax, $total);
  $stmt->execute();
  $order_id = $stmt->insert_id;
  $stmt->close();

  // Insert order items (use your existing order_items columns)
  $stmt = $conn->prepare("
    INSERT INTO order_items (order_id, user_id, product_id, quantity, price, ordered_at)
    VALUES (?, ?, ?, ?, ?, NOW())
  ");

  foreach ($items as $it) {
    $stmt->bind_param(
      "iiiid",
      $order_id,
      $user_id,
      $it['product_id'],
      $it['qty'],
      $it['price']  // unit price at purchase
    );
    $stmt->execute();
  }
  $stmt->close();

  $conn->commit();

  // Clear cart
  unset($_SESSION['cart']);

  // Go to success page
  header("Location: ../pages/order_success.php?order_id=" . $order_id);
  exit;

} catch (Throwable $e) {
  $conn->rollback();
  // Optionally log $e->getMessage()
  header("Location: ../pages/cart.php");
  exit;
}
