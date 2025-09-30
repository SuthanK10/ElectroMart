<?php
// /pages/add_product.php
session_start();

// Only admin can access
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  header('Location: ../pages/login.php');
  exit;
}

require_once __DIR__ . '/../functions/db.php';

$errors = [];
$success = '';

// Sticky defaults
$name = $image = $category = $description = $features = $specs_json = '';
$price = '';
$stock = '0';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize / normalize inputs
  $name        = trim($_POST['name'] ?? '');
  $category    = trim($_POST['category'] ?? '');
  $price       = (float)($_POST['price'] ?? 0);
  $stock       = (int)($_POST['stock'] ?? 0);
  $image       = trim($_POST['image'] ?? '');       // ← plain string path like ../images/product1.png
  $description = trim($_POST['description'] ?? '');
  $features    = trim($_POST['features'] ?? '');    // newline separated
  $specs_json  = trim($_POST['specs_json'] ?? '');  // optional JSON (key/value)

  // Validate basics
  if ($name === '')        $errors[] = 'Product name is required.';
  if ($category === '')    $errors[] = 'Category is required.';
  if ($price <= 0)         $errors[] = 'Price must be greater than 0.';
  if ($stock < 0)          $errors[] = 'Stock cannot be negative.';
  if ($image === '')       $errors[] = 'Image path is required.';

  // Validate specs JSON if provided
  $specs_arr = [];
  if ($specs_json !== '') {
    $decoded = json_decode($specs_json, true);
    if (!is_array($decoded)) {
      $errors[] = 'Specifications must be valid JSON object, e.g. {"Display":"6.1\"","RAM":"8GB"}.';
    } else {
      foreach ($decoded as $k => $v) {
        $specs_arr[] = [
          'key'   => (string)$k,
          'value' => is_scalar($v) ? (string)$v : json_encode($v)
        ];
      }
    }
  }

  if (!$errors) {
    // Insert product exactly with the path you typed
    $stmt = $conn->prepare("
      INSERT INTO products (name, image, price, category, stock, description, features)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    // types: name(s) image(s) price(d) category(s) stock(i) description(s) features(s)
    $stmt->bind_param('ssdsiss', $name, $image, $price, $category, $stock, $description, $features);
    $ok = $stmt->execute();
    $newProductId = $stmt->insert_id;
    $stmt->close();

    if (!$ok) {
      $errors[] = 'Failed to save product. Please try again.';
    } else {
      // If product_specs table exists and specs provided, insert them
      if ($specs_arr) {
        $hasSpecs = $conn->query("SHOW TABLES LIKE 'product_specs'")->num_rows > 0;
        if ($hasSpecs) {
          $ins = $conn->prepare("
            INSERT INTO product_specs (product_id, spec_key, spec_value, sort_order)
            VALUES (?, ?, ?, ?)
          ");
          $sort = 0;
          foreach ($specs_arr as $s) {
            $k = $s['key']; $v = $s['value'];
            $ins->bind_param('issi', $newProductId, $k, $v, $sort);
            $ins->execute();
            $sort++;
          }
          $ins->close();
        }
      }

      // Success + clear form
      $success = '✅ Product added successfully.';
      $name = $image = $category = $description = $features = $specs_json = '';
      $price = '';
      $stock = '0';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Product - ElectroMart Admin</title>
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

  <!-- Page -->
  <main class="max-w-3xl mx-auto px-6 py-10">
    <div class="mb-6">
      <a href="../pages/admin_dashboard.php" class="text-sm text-gray-600 hover:underline">&larr; Back to Dashboard</a>
    </div>

    <h1 class="text-2xl font-bold mb-1">Add New Product</h1>
    <p class="text-gray-600 mb-6">Enter the image path exactly as it should be used in your pages (e.g., <code>../images/product1.png</code>).</p>

    <?php if ($errors): ?>
      <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700 text-sm">
        <ul class="list-disc pl-5 space-y-1">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="bg-white rounded-2xl border shadow-sm p-6 space-y-5">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="block text-sm font-medium mb-1">Product Name *</label>
          <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"
                 class="w-full rounded-lg border px-3 py-2 focus:outline-none"
                 placeholder="Enter product name" required>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Category *</label>
          <select name="category" class="w-full rounded-lg border px-3 py-2" required>
            <option value="">Select category</option>
            <?php
              $cats = [
                'Smartphone','Laptop','Wearable','Audio','Tablet',
                'Gaming Console','Camera','Smart Home','TV'
              ];
              foreach ($cats as $c) {
                $sel = ($category === $c) ? 'selected' : '';
                echo "<option value=\"".htmlspecialchars($c)."\" $sel>".htmlspecialchars($c)."</option>";
              }
            ?>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Price ($) *</label>
          <input type="number" step="0.01" min="0.01" name="price" value="<?= htmlspecialchars($price) ?>"
                 class="w-full rounded-lg border px-3 py-2" placeholder="0.00" required>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Stock Quantity *</label>
          <input type="number" min="0" name="stock" value="<?= htmlspecialchars($stock) ?>"
                 class="w-full rounded-lg border px-3 py-2" placeholder="0" required>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium mb-1">Image Path *</label>
          <input type="text" name="image" value="<?= htmlspecialchars($image) ?>"
                 class="w-full rounded-lg border px-3 py-2"
                 placeholder="../images/product1.png" required>
          <p class="text-xs text-gray-500 mt-1">
            Tip: since your pages are in <code>/pages</code>, a relative path like <code>../images/filename.png</code> will resolve correctly.
            Make sure the file exists and includes its extension (.png/.jpg/.webp).
          </p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full rounded-lg border px-3 py-2"
                  placeholder="Product description..."><?= htmlspecialchars($description) ?></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Features (one per line)</label>
        <textarea name="features" rows="3"
                  class="w-full rounded-lg border px-3 py-2"
                  placeholder="Feature 1&#10;Feature 2&#10;Feature 3"><?= htmlspecialchars($features) ?></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Specifications (JSON, optional)</label>
        <textarea name="specs_json" rows="3"
                  class="w-full rounded-lg border px-3 py-2"
                  placeholder='{"Display":"6.1\"", "RAM":"8GB", "Storage":"128GB"}'><?= htmlspecialchars($specs_json) ?></textarea>
        <p class="text-xs text-gray-500 mt-1">If provided and <code>product_specs</code> table exists, specs will be saved as key/value rows.</p>
      </div>

      <div class="flex items-center justify-end gap-3">
        <a href="../pages/admin_dashboard.php" class="px-4 py-2 rounded-lg border hover:bg-gray-50">Cancel</a>
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-black text-white font-semibold hover:opacity-90">
          Add Product
        </button>
      </div>
    </form>

    <div class="mt-6">
      <a href="../pages/view_products.php" class="text-sm text-gray-700 hover:underline">Go to View Products &rarr;</a>
    </div>
  </main>
</body>
</html>
