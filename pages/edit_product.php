<?php
// /pages/edit_product.php
session_start();
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  header('Location: ../pages/login.php'); exit;
}
require_once __DIR__ . '/../functions/db.php';

function fetch_specs_json(mysqli $conn, int $pid): string {
  if ($conn->query("SHOW TABLES LIKE 'product_specs'")->num_rows === 0) return '';
  $stmt = $conn->prepare("SELECT spec_key, spec_value FROM product_specs WHERE product_id = ? ORDER BY sort_order, spec_key");
  $stmt->bind_param('i', $pid); $stmt->execute();
  $res = $stmt->get_result(); $out = [];
  while ($r = $res->fetch_assoc()) $out[$r['spec_key']] = $r['spec_value'];
  $stmt->close();
  return $out ? json_encode($out, JSON_UNESCAPED_SLASHES) : '';
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: ../pages/view_products.php'); exit; }

$stmt = $conn->prepare("SELECT product_id, name, image, price, category, stock, description, features FROM products WHERE product_id = ? LIMIT 1");
$stmt->bind_param('i', $id); $stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) { header('Location: ../pages/view_products.php'); exit; }

// sticky values
$name        = $product['name'];
$image       = $product['image'];
$price       = (string)$product['price'];
$category    = $product['category'];
$stock       = (string)$product['stock'];
$description = $product['description'];
$features    = $product['features'];
$specs_json  = fetch_specs_json($conn, $id);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name        = trim($_POST['name'] ?? '');
  $category    = trim($_POST['category'] ?? '');
  $price       = (float)($_POST['price'] ?? 0);
  $stock       = (int)($_POST['stock'] ?? 0);
  $image       = trim($_POST['image'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $features    = trim($_POST['features'] ?? '');
  $specs_json  = trim($_POST['specs_json'] ?? '');

  if ($name === '')     $errors[] = 'Product name is required.';
  if ($category === '') $errors[] = 'Category is required.';
  if ($price <= 0)      $errors[] = 'Price must be greater than 0.';
  if ($stock < 0)       $errors[] = 'Stock cannot be negative.';
  if ($image === '')    $errors[] = 'Image path is required.';

  $specs_arr = [];
  if ($specs_json !== '') {
    $decoded = json_decode($specs_json, true);
    if (!is_array($decoded)) {
      $errors[] = 'Specifications must be a valid JSON object.';
    } else {
      foreach ($decoded as $k => $v) {
        $specs_arr[] = ['key' => (string)$k, 'value' => is_scalar($v) ? (string)$v : json_encode($v)];
      }
    }
  }

  if (!$errors) {
    $stmt = $conn->prepare("
      UPDATE products
         SET name = ?, image = ?, price = ?, category = ?, stock = ?, description = ?, features = ?
       WHERE product_id = ?
    ");
    $stmt->bind_param('ssdsissi', $name, $image, $price, $category, $stock, $description, $features, $id);
    $ok = $stmt->execute(); $stmt->close();

    if (!$ok) {
      $errors[] = 'Failed to update product.';
    } else {
      $hasSpecs = $conn->query("SHOW TABLES LIKE 'product_specs'")->num_rows > 0;
      if ($hasSpecs) {
        $del = $conn->prepare("DELETE FROM product_specs WHERE product_id = ?");
        $del->bind_param('i', $id); $del->execute(); $del->close();
        if ($specs_arr) {
          $ins = $conn->prepare("INSERT INTO product_specs (product_id, spec_key, spec_value, sort_order) VALUES (?, ?, ?, ?)");
          $sort = 0;
          foreach ($specs_arr as $s) {
            $k = $s['key']; $v = $s['value'];
            $ins->bind_param('issi', $id, $k, $v, $sort);
            $ins->execute(); $sort++;
          }
          $ins->close();
        }
      }
      $success = '✅ Product updated.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Product - ElectroMart Admin</title>
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
    <div class="mb-6 flex items-center justify-between">
      <a href="../pages/view_products.php" class="text-sm text-gray-600 hover:underline">&larr; Back to Products</a>
      <span class="text-xs text-gray-500">Product ID #<?= (int)$id ?></span>
    </div>

    <?php if ($errors): ?>
      <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 text-sm">
        <ul class="list-disc pl-5 space-y-1">
          <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <!-- Big card with two columns -->
    <form method="post" class="rounded-2xl border bg-white shadow-sm">
      <!-- Header strip -->
      <div class="px-6 py-4 border-b bg-gray-50 rounded-t-2xl">
        <h1 class="text-xl font-semibold">Edit Product</h1>
        <p class="text-sm text-gray-500 mt-1">Update details and save changes below.</p>
      </div>

      <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left column: Main details -->
        <section class="lg:col-span-2 space-y-6">
          <!-- Basic Info -->
          <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-medium mb-1">Product Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="w-full rounded-lg border px-3 py-2" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Category *</label>
                <select name="category" class="w-full rounded-lg border px-3 py-2" required>
                  <?php
                    $cats = ['Smartphone','Laptop','Wearable','Audio','Tablet','Gaming Console','Camera','Smart Home','TV'];
                    foreach ($cats as $c) {
                      $sel = ($category === $c) ? 'selected' : '';
                      echo "<option value=\"".htmlspecialchars($c)."\" $sel>".htmlspecialchars($c)."</option>";
                    }
                  ?>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Price ($) *</label>
                <input type="number" step="0.01" min="0.01" name="price" value="<?= htmlspecialchars($price) ?>" class="w-full rounded-lg border px-3 py-2" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Stock Quantity *</label>
                <input type="number" min="0" name="stock" value="<?= htmlspecialchars($stock) ?>" class="w-full rounded-lg border px-3 py-2" required>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Image Path *</label>
                <input type="text" name="image" value="<?= htmlspecialchars($image) ?>" class="w-full rounded-lg border px-3 py-2" placeholder="../images/product1.png" required>
                <p class="text-xs text-gray-500 mt-1">Use a valid relative/absolute path (e.g., <code>../images/item.png</code>).</p>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Description</h2>
            <textarea name="description" rows="4" class="w-full rounded-lg border px-3 py-2" placeholder="Product description..."><?= htmlspecialchars($description) ?></textarea>
          </div>

          <!-- Features -->
          <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Features</h2>
            <textarea name="features" rows="4" class="w-full rounded-lg border px-3 py-2" placeholder="One per line"><?= htmlspecialchars($features) ?></textarea>
          </div>
        </section>

        <!-- Right column: Specs -->
        <aside class="lg:col-span-1">
          <div class="rounded-xl border bg-gray-50 p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Specifications (JSON) — Optional</h2>
            <textarea name="specs_json" rows="14" class="w-full rounded-lg border px-3 py-2"
              placeholder='{"Display":"6.1\"", "RAM":"8GB"}'><?= htmlspecialchars($specs_json) ?></textarea>
            <p class="text-xs text-gray-500 mt-2">
              If <code>product_specs</code> exists, current specs will be replaced by this JSON object.
            </p>
          </div>
        </aside>
      </div>

      <!-- Footer actions -->
      <div class="px-6 py-4 border-t bg-white rounded-b-2xl flex items-center justify-end gap-3">
        <a href="../pages/view_products.php" class="px-4 py-2 rounded-lg border hover:bg-gray-50">Cancel</a>
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-black text-white font-semibold hover:opacity-90">
          Save Changes
        </button>
      </div>
    </form>
  </main>
</body>
</html>
