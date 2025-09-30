<?php
// /pages/delete_product.php
session_start();

// Admin-only guard
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  header('Location: ../pages/login.php');
  exit;
}

require_once __DIR__ . '/../functions/db.php';

function money($n){ return number_format((float)$n, 2); }

$notice = '';
$error  = '';

// Handle delete (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pid = (int)($_POST['product_id'] ?? 0);
  if ($pid <= 0) {
    $error = 'Invalid product id.';
  } else {
    // Optional: delete specs if table exists
    $hasSpecs = $conn->query("SHOW TABLES LIKE 'product_specs'")->num_rows > 0;
    if ($hasSpecs) {
      $delSpecs = $conn->prepare("DELETE FROM product_specs WHERE product_id = ?");
      $delSpecs->bind_param('i', $pid);
      $delSpecs->execute();
      $delSpecs->close();
    }

    // Delete product
    $del = $conn->prepare("DELETE FROM products WHERE product_id = ? LIMIT 1");
    $del->bind_param('i', $pid);
    if ($del->execute() && $del->affected_rows > 0) {
      $notice = 'Product deleted successfully.';
    } else {
      $error = 'Failed to delete product (it may not exist).';
    }
    $del->close();
  }
}

// Fetch products to display
$rows = [];
$stmt = $conn->prepare("SELECT product_id, name, category, price, stock FROM products ORDER BY product_id ASC");
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
$stmt->close();

$total = count($rows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delete Products - ElectroMart Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen text-gray-900">
  <!-- Top bar -->
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
      <a href="../pages/admin_dashboard.php" class="text-xl font-bold flex items-center gap-2">
        <img src="../images/electromart2.png" alt="ElectroMart Logo" class="w-[110px] md:w-[130px]">
        Admin
      </a>
      <div class="flex items-center gap-4">
        <span class="text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="../pages/logout.php" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border bg-gray-100 hover:bg-gray-200 text-sm">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-6 py-10">
    <div class="rounded-2xl border bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
        <div>
          <h1 class="text-lg font-semibold">Delete Products</h1>
          <p class="text-xs text-gray-500 mt-0.5">Select products to delete</p>
        </div>
        <a href="../pages/admin_dashboard.php" class="px-3 py-1.5 rounded-lg border text-sm hover:bg-gray-100">Back to Home</a>
      </div>

      <?php if ($notice): ?>
        <div class="mx-6 mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700 text-sm">
          <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="mx-6 mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-rose-700 text-sm">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <?php if (!$rows): ?>
        <div class="px-6 py-10 text-center text-gray-600">No products found.</div>
      <?php else: ?>
        <ul class="divide-y mt-4">
          <?php foreach ($rows as $p): ?>
            <li class="px-6 py-4 flex items-center justify-between">
              <div class="min-w-0">
                <div class="font-medium truncate"><?= htmlspecialchars($p['name']) ?></div>
                <div class="text-xs text-gray-500 mt-0.5">
                  <?= htmlspecialchars($p['category']) ?> •
                  $<?= money($p['price']) ?> •
                  Stock: <?= (int)$p['stock'] ?>
                </div>
              </div>
              <form method="post" class="ml-4">
                <input type="hidden" name="product_id" value="<?= (int)$p['product_id'] ?>">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700">
                  <i class="fa-solid fa-trash-can"></i> Delete
                </button>
              </form>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="px-6 py-4 text-xs text-gray-500 border-t">
          Total products: <?= (int)$total ?>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
