<?php
// /pages/productsview.php
include '../includes/header.php';
include '../functions/db.php';

// Validate & get ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(404);
  exit('<div class="max-w-3xl mx-auto p-10 text-center">Product not found.</div>');
}

// Fetch product (+ features)
$stmt = $conn->prepare("
  SELECT product_id, name, image, price, category, stock, description, features
  FROM products
  WHERE product_id = ?
  LIMIT 1
");
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
  http_response_code(404);
  exit('<div class="max-w-3xl mx-auto p-10 text-center">Product not found.</div>');
}

// ----- Fetch specs (requires table: product_specs) -----
$specRows = [];
if ($stmt = $conn->prepare("SELECT spec_key, spec_value FROM product_specs WHERE product_id = ? ORDER BY sort_order ASC, spec_key ASC")) {
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  $specRows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  $stmt->close();
}

// ----- Fetch reviews + averages (requires table: product_reviews) -----
$avgRating = null; $reviewsCount = 0; $reviews = [];
if ($stmt = $conn->prepare("SELECT ROUND(AVG(rating),1) AS avg_rating, COUNT(*) AS cnt FROM product_reviews WHERE product_id = ?")) {
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  if ($row) { $avgRating = $row['avg_rating']; $reviewsCount = (int)$row['cnt']; }
  $stmt->close();
}
if ($stmt = $conn->prepare("SELECT name, rating, comment, created_at FROM product_reviews WHERE product_id = ? ORDER BY created_at DESC")) {
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  $reviews = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  $stmt->close();
}

// Helpers
function money($n){ return number_format((float)$n, 2); }
function stars($n){
  $n = max(0, min(5, (float)$n));
  $full = floor($n);
  $half = ($n - $full) >= 0.5 ? 1 : 0;
  $empty = 5 - $full - $half;
  return str_repeat('★', $full) . str_repeat('☆', $half) . str_repeat('✩', $empty);
}

// Parse features -> bullet list
$features = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $product['features'] ?? '')));
?>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6">
      <a href="../pages/index.php" class="hover:underline">Home</a>
      <span class="mx-2">/</span>
      <a href="products.php" class="hover:underline">Products</a>
      <span class="mx-2">/</span>
      <span class="text-gray-700"><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
      <!-- Left: Image / gallery -->
      <div>
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
          <div class="h-[420px] md:h-[520px] w-full flex items-center justify-center p-6">
            <img
              id="mainImage"
              src="<?= htmlspecialchars($product['image']) ?>"
              alt="<?= htmlspecialchars($product['name']) ?>"
              class="max-h-full max-w-full object-contain"
              loading="lazy"
            >
          </div>
        </div>
      </div>

      <!-- Right: Content -->
      <div>
        <div class="mb-2 flex items-center gap-3">
          <?php if ((int)$product['stock'] > 0): ?>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
              In Stock
            </span>
          <?php else: ?>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700">
              Out of Stock
            </span>
          <?php endif; ?>

          <?php if ($reviewsCount > 0): ?>
            <span class="text-yellow-500 text-sm"><?= stars($avgRating) ?></span>
            <span class="text-sm text-gray-600"><?= $avgRating ?> (<?= $reviewsCount ?> reviews)</span>
          <?php endif; ?>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="mt-1 text-gray-500"><?= htmlspecialchars($product['category']) ?></p>

        <div class="mt-4 flex items-end gap-3">
          <span class="text-3xl font-extrabold">$<?= money($product['price']) ?></span>
        </div>

        <?php if (!empty($product['description'])): ?>
          <p class="mt-5 text-gray-700 leading-7">
            <?= nl2br(htmlspecialchars($product['description'])) ?>
          </p>
        <?php endif; ?>

        <!-- Quantity + Add to Cart -->
        <form action="../pages/cart_add.php" method="post" class="mt-6">
          <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
          <label class="block text-sm font-medium mb-2">Quantity</label>
          <div class="flex items-center gap-3">
            <input type="number" name="qty" value="1" min="1" class="w-24 rounded-lg border px-3 py-2" />
            <button type="submit"
              <?= ((int)$product['stock'] <= 0) ? 'disabled' : '' ?>
              class="flex-1 inline-flex items-center justify-center rounded-xl px-6 py-3 font-semibold text-white
                     <?= ((int)$product['stock'] > 0) ? 'bg-black hover:opacity-90' : 'bg-gray-400 cursor-not-allowed' ?>">
              Add to Cart - $<?= money($product['price']) ?>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mt-12 rounded-2xl border bg-white shadow-sm">
      <div class="flex gap-1 border-b bg-gray-50 rounded-t-2xl">
        <button class="tab-btn px-5 py-3 text-sm font-semibold rounded-t-2xl bg-white">Features</button>
        <button class="tab-btn px-5 py-3 text-sm font-semibold">Specifications</button>
        <button class="tab-btn px-5 py-3 text-sm font-semibold">Reviews (<?= $reviewsCount ?>)</button>
      </div>

      <!-- Features panel -->
      <div class="tab-panel p-6">
        <?php if ($features): ?>
          <ul class="list-disc pl-6 space-y-2 text-gray-700">
            <?php foreach ($features as $f): ?>
              <li><?= htmlspecialchars($f) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-600">No features listed for this product.</p>
        <?php endif; ?>
      </div>

      <!-- Specifications panel -->
      <div class="tab-panel p-6 hidden">
        <?php if ($specRows): ?>
          <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3">
            <?php foreach ($specRows as $s): ?>
              <div class="flex justify-between border-b py-2">
                <dt class="text-gray-600"><?= htmlspecialchars($s['spec_key']) ?></dt>
                <dd class="font-medium"><?= htmlspecialchars($s['spec_value']) ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        <?php else: ?>
          <p class="text-gray-600">No technical specifications available.</p>
        <?php endif; ?>
      </div>

      <!-- Reviews panel -->
      <div class="tab-panel p-6 hidden">
        <?php if ($reviews): ?>
          <ul class="space-y-5">
            <?php foreach ($reviews as $r): ?>
              <li class="border rounded-xl p-4">
                <div class="flex items-center justify-between">
                  <div class="font-semibold"><?= htmlspecialchars($r['name']) ?></div>
                  <div class="text-yellow-500"><?= stars($r['rating']) ?></div>
                </div>
                <?php if (!empty($r['comment'])): ?>
                  <p class="mt-2 text-gray-700"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                <?php endif; ?>
                <div class="mt-2 text-xs text-gray-500">
                  <?= htmlspecialchars($r['created_at'] ? date('M j, Y', strtotime($r['created_at'])) : '') ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-600">No reviews yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    // Simple tabs
    const btns = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');
    btns.forEach((b,i)=>b.addEventListener('click', ()=>{
      btns.forEach((x,j)=>x.classList.toggle('bg-white', j===i));
      panels.forEach((p,j)=>p.classList.toggle('hidden', j!==i));
    }));
  </script>
</body>
</html>
<?php include '../includes/footer.php'; ?>
