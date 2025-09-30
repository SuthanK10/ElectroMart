<?php
session_start();
require_once __DIR__ . '/../functions/db.php';
include '../includes/header.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

$stmt = $conn->prepare("SELECT o.*, u.email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.user_id 
                        WHERE o.order_id = ? LIMIT 1");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
  header("Location: ../pages/products.php");
  exit;
}
?>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-3xl mx-auto px-6 py-16">
    <div class="bg-white rounded-2xl border shadow-sm p-10 text-center">
      <h1 class="text-2xl md:text-3xl font-bold mb-2">Order Successful!</h1>
      <p class="text-gray-600">Your order #<?= $order['order_id'] ?> is confirmed.</p>

      <p class="mt-4 text-gray-600">
        We’ve sent the details to <span class="font-medium"><?= htmlspecialchars($order['email']) ?></span>.
      </p>

      <div class="mt-6 text-lg font-semibold">
        Total Paid: $<?= number_format((float)$order['total'], 2) ?>
      </div>

      <a href="../pages/products.php" class="inline-block mt-8">
        <button class="rounded-lg bg-black text-white px-6 py-3 font-semibold hover:opacity-90">
          Back to Home Page
        </button>
      </a>
    </div>
  </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
