<?php
// /pages/view_products.php
session_start();

// Admin-only
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  header('Location: ../pages/login.php');
  exit;
}

require_once __DIR__ . '/../functions/db.php';

function money($n){ return number_format((float)$n, 2); }

// Fetch products
$rows = [];
$stmt = $conn->prepare("SELECT product_id, name, image, category, price, stock FROM products ORDER BY product_id ASC");
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
  <title>View Products - ElectroMart Admin</title>
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
        <a href="../pages/logout.php"
           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border bg-gray-100 hover:bg-gray-200 text-sm">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold">All Products <span class="text-gray-500 text-base">(<?= (int)$total ?>)</span></h1>
      <div class="flex items-center gap-3">
        <a href="../pages/add_product.php" class="px-4 py-2 rounded-lg bg-black text-white text-sm font-semibold hover:opacity-90">
          <i class="fa-solid fa-plus mr-2"></i>Add Product
        </a>
        <a href="../pages/admin_dashboard.php" class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50">Back to Home</a>
      </div>
    </div>

    <?php if (!$rows): ?>
      <div class="rounded-2xl border bg-white p-8 text-center text-gray-600">
        No products found.
        <div class="mt-4">
          <a href="../pages/add_product.php" class="px-4 py-2 rounded-lg bg-black text-white text-sm font-semibold hover:opacity-90">
            Add your first product
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="rounded-2xl border bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="text-left px-5 py-3 font-semibold">Product</th>
              <th class="text-left px-5 py-3 font-semibold">Category</th>
              <th class="text-left px-5 py-3 font-semibold">Price</th>
              <th class="text-left px-5 py-3 font-semibold">Stock</th>
              <th class="text-left px-5 py-3 font-semibold">Status</th>
              <th class="text-right px-5 py-3 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php foreach ($rows as $p): ?>
              <?php
                $inStock = ((int)$p['stock'] > 0);
                $statusLabel = $inStock ? 'Active' : 'Out of Stock';
                $statusClasses = $inStock
                  ? 'bg-emerald-50 text-emerald-700'
                  : 'bg-rose-50 text-rose-700';
                $thumb = $p['image'] ?: '/SSP_Assignment/images/placeholder.png';
              ?>
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                  <div class="flex items-center gap-3">
                    <img src="<?= htmlspecialchars($thumb) ?>" alt="" class="w-10 h-10 object-contain bg-white rounded border">
                    <div>
                      <div class="font-medium"><?= htmlspecialchars($p['name']) ?></div>
                      <div class="text-xs text-gray-500">#<?= (int)$p['product_id'] ?></div>
                    </div>
                  </div>
                </td>
                <td class="px-5 py-3"><?= htmlspecialchars($p['category']) ?></td>
                <td class="px-5 py-3">$<?= money($p['price']) ?></td>
                <td class="px-5 py-3"><?= (int)$p['stock'] ?></td>
                <td class="px-5 py-3">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClasses ?>">
                    <?= $statusLabel ?>
                  </span>
                </td>
                <td class="px-5 py-3">
                  <div class="flex items-center gap-3 justify-end">
                    <!-- View (public product page) -->
                    <a title="View" href="../pages/products.php?id=<?= (int)$p['product_id'] ?>"
                       class="text-gray-600 hover:text-black">
                      <i class="fa-regular fa-eye"></i>
                    </a>
                    <!-- Edit -->
                    <a title="Edit" href="../pages/edit_product.php?id=<?= (int)$p['product_id'] ?>"
                       class="text-gray-600 hover:text-black">
                      <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <!-- Delete (GET to a confirm/delete page you already have) -->
                    <a title="Delete" href="../pages/delete_product.php?id=<?= (int)$p['product_id'] ?>"
                       class="text-rose-600 hover:text-rose-700">
                      <i class="fa-regular fa-trash-can"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
