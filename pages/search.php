<?php
include '../functions/db.php'; // your database connection file
include '../includes/header.php';     // keep your header layout

$query = $_GET['query'] ?? '';

if (!empty($query)) {
    $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $search = "%" . $query . "%";
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<div class="max-w-7xl mx-auto px-6 py-8">
  <h2 class="text-2xl font-bold mb-6">Search Results for: "<?php echo htmlspecialchars($query); ?>"</h2>

  <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php if (!empty($result) && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg">
          <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-48 object-contain rounded mb-4">
          <h3 class="mt-2 text-lg font-semibold"><?php echo $row['name']; ?></h3>
          <p class="text-gray-600 text-sm"><?php echo $row['description']; ?></p>
          <p class="text-black-600 font-bold mt-2">$<?php echo $row['price']; ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No products found.</p>
    <?php endif; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
