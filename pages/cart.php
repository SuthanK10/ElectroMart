<?php
// /pages/cart.php
session_start();
include '../includes/header.php';
include '../functions/db.php';

// Handle update/remove actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pid = (int)($_POST['product_id'] ?? 0);
  $action = $_POST['action'] ?? '';

  if ($pid > 0 && isset($_SESSION['cart'][$pid])) {
    if ($action === 'update') {
      $newQty = max(1, (int)($_POST['qty'] ?? 1)); // never < 1
      $_SESSION['cart'][$pid] = $newQty;
    } elseif ($action === 'remove') {
      unset($_SESSION['cart'][$pid]);
    }
  }

  // Refresh to avoid form resubmission
  header("Location: ../pages/cart.php");
  exit;
}

// Build cart data
$cart = $_SESSION['cart'] ?? [];
$items = [];
$subtotal = 0.0;

if ($cart) {
  $ids = array_map('intval', array_keys($cart));
  $placeholders = implode(',', array_fill(0, count($ids), '?'));
  $types = str_repeat('i', count($ids));

  $stmt = $conn->prepare("SELECT product_id, name, price, image FROM products WHERE product_id IN ($placeholders)");
  $stmt->bind_param($types, ...$ids);
  $stmt->execute();
  $res = $stmt->get_result();

  while ($row = $res->fetch_assoc()) {
    $pid = (int)$row['product_id'];
    $qty = (int)$cart[$pid];
    $row['qty'] = $qty;
    $row['line_total'] = $row['price'] * $qty;
    $subtotal += $row['line_total'];
    $items[] = $row;
  }
  $stmt->close();
}

function money($n){ return number_format((float)$n, 2); }
?>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-6xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-1">Shopping Cart</h1>
    <p class="text-gray-500 mb-8">
      <?= count($cart) ?> item<?= count($cart)===1?'':'s' ?> in your cart
    </p>

    <?php if (!$items): ?>
      <div class="rounded-xl border bg-white p-6 text-center text-gray-600">
        Your cart is empty.
        <div class="mt-4">
          <a href="../pages/products.php">
            <button class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
              ← Continue Shopping
            </button>
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Items -->
        <div class="lg:col-span-2 space-y-6">
          <?php foreach ($items as $it): ?>
            <div class="rounded-xl border bg-white p-5 flex items-center gap-5">
              <!-- Image -->
              <img src="<?= htmlspecialchars($it['image']) ?>" alt="" 
                   class="w-20 h-20 object-contain rounded-md bg-white">

              <!-- Details -->
              <div class="flex-1">
                <div class="font-semibold"><?= htmlspecialchars($it['name']) ?></div>
                <div class="text-sm text-gray-500 mt-1">$<?= money($it['price']) ?></div>

                <!-- Quantity form -->
                <form action="cart.php" method="post" class="mt-3 flex items-center gap-2">
                  <input type="hidden" name="product_id" value="<?= (int)$it['product_id'] ?>">
                  <input type="hidden" name="action" value="update">

                  <div class="flex items-center border rounded-lg overflow-hidden">
                    <!-- Minus -->
                    <button type="submit" name="qty" value="<?= (int)$it['qty'] - 1 ?>"
                            class="px-3 py-1 text-lg font-bold hover:bg-gray-100">−</button>

                    <!-- Qty display -->
                    <span class="px-4"><?= (int)$it['qty'] ?></span>

                    <!-- Plus -->
                    <button type="submit" name="qty" value="<?= (int)$it['qty'] + 1 ?>"
                            class="px-3 py-1 text-lg font-bold hover:bg-gray-100">+</button>
                  </div>
                </form>
              </div>

              <!-- Line total + remove -->
              <div class="text-right">
                <div class="font-semibold">$<?= money($it['line_total']) ?></div>
                <form action="cart.php" method="post" class="mt-2">
                  <input type="hidden" name="product_id" value="<?= (int)$it['product_id'] ?>">
                  <input type="hidden" name="action" value="remove">
                  <button type="submit" class="text-red-500 hover:text-red-700 text-xl">✖</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="mt-4">
            <a href="../pages/products.php">
              <button class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
                ← Continue Shopping
              </button>
            </a>
          </div>
        </div>

        <!-- Summary -->
        <div>
          <div class="rounded-xl border bg-white p-5">
            <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
            <div class="flex justify-between py-2">
              <span class="text-gray-600">Subtotal</span>
              <span>$<?= money($subtotal) ?></span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-gray-600">Shipping</span>
              <span>Free</span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-gray-600">Tax</span>
              <span>$0.00</span>
            </div>
            <hr class="my-3">
            <div class="flex justify-between py-2 font-semibold">
              <span>Total</span>
              <span>$<?= money($subtotal) ?></span>
            </div>

            <!-- ✅ Updated Place Order button -->
            <form method="post" action="../pages/order_place.php">
              <button type="submit" class="w-full mt-4 rounded-lg bg-black text-white py-3 font-semibold">
                Place Order
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
<?php include '../includes/footer.php'; ?>
