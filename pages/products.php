<?php 
// /pages/products.php
include '../includes/header.php'; 
include '../functions/db.php';

// --- Collect filters from GET ---
$category = $_GET['category'] ?? '';
$minPrice = $_GET['minPrice'] ?? '';
$maxPrice = $_GET['maxPrice'] ?? '';
$stock    = $_GET['stock'] ?? '';

// active filter count (for mobile badge)
$activeFilters = 0;
foreach ([$category, $minPrice, $maxPrice, $stock] as $v) {
  if ($v !== '' && $v !== null) $activeFilters++;
}

// --- Build SQL with filters ---
$sql = "SELECT product_id, name, image, category, price, stock FROM products WHERE 1=1";
$params = [];
$types  = "";

// Category filter
if ($category !== '') {
  $sql .= " AND category = ?";
  $params[] = $category;
  $types   .= "s";
}

// Price range filter
if ($minPrice !== '' && is_numeric($minPrice)) {
  $sql .= " AND price >= ?";
  $params[] = (float)$minPrice;
  $types   .= "d";
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
  $sql .= " AND price <= ?";
  $params[] = (float)$maxPrice;
  $types   .= "d";
}

// Stock filter
if ($stock === "in") {
  $sql .= " AND stock > 0";
} elseif ($stock === "out") {
  $sql .= " AND stock = 0";
}

// Prepare + execute
$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// --- Fetch unique categories for filter ---
$catResult = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
?>
<div class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-10">
  <!-- Header + Mobile Filter Toggle -->
  <div class="flex items-start justify-between gap-3 mb-6 md:mb-8">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold">All Products</h1>
      <p class="text-gray-600">Discover our complete collection of electronics</p>
    </div>

    <!-- Filter button (mobile only) -->
    <button id="filterBtn"
      class="md:hidden inline-flex items-center gap-2 px-3 py-2 rounded-lg border bg-white shadow-sm text-sm">
      <i class="fa-solid fa-sliders"></i>
      Filters
      <?php if ($activeFilters > 0): ?>
        <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs rounded-full bg-black text-white">
          <?= (int)$activeFilters ?>
        </span>
      <?php endif; ?>
    </button>
  </div>

  <div class="flex flex-col md:flex-row md:items-start gap-6 md:gap-8">
    <!-- Sidebar Filters (desktop) -->
    <aside class="hidden md:block w-64 shrink-0">
      <div class="bg-white rounded-xl shadow p-6 sticky top-4">
        <form method="GET" class="space-y-6">
          <!-- Category -->
          <div>
            <h3 class="font-semibold mb-2">Category</h3>
            <select name="category" class="w-full border rounded px-3 py-2">
              <option value="">All Categories</option>
              <?php while ($cat = $catResult->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($cat['category']) ?>"
                  <?= ($category === $cat['category']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['category']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Price -->
          <div>
            <h3 class="font-semibold mb-2">Price Range ($)</h3>
            <div class="flex gap-2">
              <input type="number" name="minPrice" value="<?= htmlspecialchars($minPrice) ?>"
                     placeholder="Min" class="w-1/2 border rounded px-2 py-1">
              <input type="number" name="maxPrice" value="<?= htmlspecialchars($maxPrice) ?>"
                     placeholder="Max" class="w-1/2 border rounded px-2 py-1">
            </div>
          </div>

          <!-- Stock -->
          <div>
            <h3 class="font-semibold mb-2">Availability</h3>
            <select name="stock" class="w-full border rounded px-3 py-2">
              <option value="">All</option>
              <option value="in"  <?= $stock === "in" ? 'selected' : '' ?>>In Stock</option>
              <option value="out" <?= $stock === "out" ? 'selected' : '' ?>>Out of Stock</option>
            </select>
          </div>

          <!-- Buttons -->
          <div class="flex justify-between">
            <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-900">
              Apply
            </button>
            <a href="products.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
              Reset
            </a>
          </div>
        </form>
      </div>
    </aside>

    <!-- Mobile Filters (collapsible) -->
    <div id="filterPanel" class="md:hidden hidden">
      <div class="bg-white rounded-xl shadow p-4">
        <form method="GET" class="space-y-4">
          <!-- Category -->
          <div>
            <label class="block text-sm font-semibold mb-1">Category</label>
            <select name="category" class="w-full border rounded px-3 py-2">
              <option value="">All Categories</option>
              <?php
              $catsMobile = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
              while ($c = $catsMobile->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($c['category']) ?>"
                  <?= ($category === $c['category']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['category']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Price -->
          <div>
            <label class="block text-sm font-semibold mb-1">Price Range ($)</label>
            <div class="grid grid-cols-2 gap-2">
              <input type="number" name="minPrice" value="<?= htmlspecialchars($minPrice) ?>"
                     placeholder="Min" class="border rounded px-3 py-2">
              <input type="number" name="maxPrice" value="<?= htmlspecialchars($maxPrice) ?>"
                     placeholder="Max" class="border rounded px-3 py-2">
            </div>
          </div>

          <!-- Stock -->
          <div>
            <label class="block text-sm font-semibold mb-1">Availability</label>
            <select name="stock" class="w-full border rounded px-3 py-2">
              <option value="">All</option>
              <option value="in"  <?= $stock === "in" ? 'selected' : '' ?>>In Stock</option>
              <option value="out" <?= $stock === "out" ? 'selected' : '' ?>>Out of Stock</option>
            </select>
          </div>

          <div class="flex items-center justify-between gap-2 pt-1">
            <a href="products.php"
               class="w-1/2 text-center bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
              Reset
            </a>
            <button type="submit"
                    class="w-1/2 bg-black text-white px-4 py-2 rounded hover:bg-gray-900">
              Apply
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="flex-1">
      <?php if ($result->num_rows > 0): ?>
        <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-8">
          <?php while ($row = $result->fetch_assoc()): ?>
            <a href="productsview.php?id=<?= (int)$row['product_id'] ?>"
               class="block bg-white shadow rounded-xl overflow-hidden hover:shadow-xl 
                      transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
              <div class="h-44 sm:h-48 md:h-56 w-full bg-white flex items-center justify-center overflow-hidden">
                <img src="<?= htmlspecialchars($row['image']) ?>" 
                     alt="<?= htmlspecialchars($row['name']) ?>" 
                     class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110">
              </div>
              <div class="p-4">
                <h2 class="text-base md:text-lg font-semibold text-gray-800 group-hover:underline line-clamp-2">
                  <?= htmlspecialchars($row['name']) ?>
                </h2>
                <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($row['category']) ?></p>
                <div class="flex items-center justify-between mb-4">
                  <span class="text-lg md:text-xl font-bold text-gray-900">
                    $<?= number_format((float)$row['price'], 2) ?>
                  </span>
                  <?php if ((int)$row['stock'] > 0): ?>
                    <span class="text-green-600 text-xs md:text-sm">In Stock</span>
                  <?php else: ?>
                    <span class="text-red-500 text-xs md:text-sm">Out of Stock</span>
                  <?php endif; ?>
                </div>
                <div class="w-full bg-black text-white text-center py-2 rounded-lg 
                            transition-colors duration-300 group-hover:bg-gray-800 text-sm">
                  View Details
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-600 mt-4">No products found matching your filters.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  // Mobile filter toggle
  const filterBtn = document.getElementById('filterBtn');
  const filterPanel = document.getElementById('filterPanel');
  if (filterBtn && filterPanel) {
    filterBtn.addEventListener('click', () => {
      filterPanel.classList.toggle('hidden');
    });
  }
</script>

<?php include '../includes/footer.php'; ?>
