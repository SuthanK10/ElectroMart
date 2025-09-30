<?php
// /pages/purchases.php
session_start();
if (empty($_SESSION['user'])) {
  header('Location: ../pages/login.php');
  exit;
}

require_once __DIR__ . '/../functions/db.php';
include __DIR__ . '/../includes/header.php';

$userId = (int)$_SESSION['user']['id'];

$sql = "
  SELECT
    o.order_id,
    o.created_at,
    o.total,
    oi.quantity,
    oi.price,
    (oi.quantity * oi.price) AS line_total,
    p.product_id,
    p.name AS product_name,
    p.image
  FROM orders o
  JOIN order_items oi ON oi.order_id = o.order_id
  JOIN products p     ON p.product_id = oi.product_id
  WHERE o.user_id = ?
  ORDER BY o.created_at DESC, o.order_id DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$res = $stmt->get_result();

$orders = [];
while ($r = $res->fetch_assoc()) {
  $oid = (int)$r['order_id'];
  if (!isset($orders[$oid])) {
    $orders[$oid] = [
      'order_id'   => $oid,
      'created_at' => $r['created_at'],
      'total'      => (float)$r['total'],
      'items'      => []
    ];
  }
  $orders[$oid]['items'][] = [
    'product_id'   => (int)$r['product_id'],
    'product_name' => $r['product_name'],
    'image'        => $r['image'],
    'quantity'     => (int)$r['quantity'],
    'price'   => (float)$r['price'],
    'line_total'   => (float)$r['line_total']
  ];
}
?>

<div class="max-w-7xl mx-auto px-6 py-10">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold">My Purchases</h1>
    <a href="products.php" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900">Continue Shopping</a>
  </div>

  <?php if (empty($orders)): ?>
    <div class="bg-white border rounded-xl p-8 text-center">
      <p class="text-gray-600 mb-4">You haven’t purchased anything yet.</p>
      <a href="products.php" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900">Browse Products</a>
    </div>
  <?php else: ?>
    <div class="space-y-6">
      <?php foreach ($orders as $o): ?>
        <div class="bg-white rounded-2xl shadow border">
          <!-- Order header -->
          <div class="flex items-center justify-between px-6 py-4 border-b">
            <div>
              <p class="text-sm text-gray-500">Order #<?= $o['order_id'] ?></p>
              <p class="font-semibold"><?= date('M j, Y · g:i A', strtotime($o['created_at'])) ?></p>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-500">Total</p>
              <p class="text-xl font-bold">$<?= number_format($o['total'], 2) ?></p>
            </div>
          </div>

          <!-- Items -->
          <div class="divide-y">
            <?php foreach ($o['items'] as $it): ?>
              <div class="px-6 py-4 flex items-center gap-4">
                <div class="h-16 w-16 bg-white border rounded overflow-hidden flex items-center justify-center">
                  <img src="<?= htmlspecialchars($it['image']) ?>" alt="<?= htmlspecialchars($it['product_name']) ?>" class="max-h-16 object-contain">
                </div>
                <div class="flex-1">
                  <a href="productsview.php?id=<?= $it['product_id'] ?>"
                     class="font-semibold hover:underline"><?= htmlspecialchars($it['product_name']) ?></a>
                  <div class="text-sm text-gray-500">Quantity: <?= $it['quantity'] ?></div>
                </div>
                <div class="text-right">
                  <div class="text-sm text-gray-500">Price</div>
                  <div class="font-semibold">$<?= number_format($it['price'], 2) ?></div>
                </div>
                <div class="text-right w-28">
                  <div class="text-sm text-gray-500">Line Total</div>
                  <div class="font-semibold">$<?= number_format($it['line_total'], 2) ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
