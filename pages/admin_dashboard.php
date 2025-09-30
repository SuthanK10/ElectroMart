<?php
// /pages/admin_dashboard.php
session_start();

// Only admin can access
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  header('Location: ../pages/index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - ElectroMart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Top bar -->
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
      <h1 class="text-xl font-bold flex items-center gap-2">
        <img src="../images/electromart2.png" alt="ElectroMart Logo" class="w-[110px] md:w-[130px]">
        Admin
      </h1>

      <div class="flex items-center gap-4">
        <span class="text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="../pages/logout.php"
           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border bg-gray-100 hover:bg-gray-200 text-sm">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-5xl mx-auto px-6 py-12">
    <h2 class="text-2xl font-bold text-center mb-2">Product Management</h2>
    <p class="text-center text-gray-600 mb-10">Choose an action to manage your products</p>

    <!-- 2x2 grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-3xl mx-auto">
      <!-- Add Product -->
      <a href="../pages/add_product.php"
         class="rounded-2xl border bg-white p-7 shadow-sm hover:shadow-md transition text-center group">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
          <i class="fa-solid fa-plus text-2xl text-green-600"></i>
        </div>
        <h3 class="font-semibold text-lg">Add Product</h3>
        <p class="text-sm text-gray-500 mt-1">Add new products to your inventory</p>
      </a>

      <!-- Edit Products -->
      <a href="../pages/edit_product.php"
         class="rounded-2xl border bg-white p-7 shadow-sm hover:shadow-md transition text-center group">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
          <i class="fa-solid fa-pen text-xl text-orange-500"></i>
        </div>
        <h3 class="font-semibold text-lg">Edit Products</h3>
        <p class="text-sm text-gray-500 mt-1">Modify existing product details</p>
      </a>

      <!-- View Products -->
      <a href="../pages/view_products.php"
         class="rounded-2xl border bg-white p-7 shadow-sm hover:shadow-md transition text-center group">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
          <i class="fa-solid fa-eye text-xl text-blue-600"></i>
        </div>
        <h3 class="font-semibold text-lg">View Products</h3>
        <p class="text-sm text-gray-500 mt-1">Browse and manage all products</p>
      </a>

      <!-- Delete Products -->
      <a href="../pages/delete_product.php"
         class="rounded-2xl border bg-white p-7 shadow-sm hover:shadow-md transition text-center group">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center">
          <i class="fa-solid fa-trash text-xl text-rose-600"></i>
        </div>
        <h3 class="font-semibold text-lg">Delete Products</h3>
        <p class="text-sm text-gray-500 mt-1">Remove products from inventory</p>
      </a>
    </div>

    <!-- Analytics (centered, its own row) -->
    <div class="mt-8 flex justify-center">
      <a href="../pages/analytics.php"
         class="rounded-2xl border bg-white p-7 shadow-sm hover:shadow-md transition text-center w-64">
        <div class="mx-auto mb-4 w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
          <i class="fa-solid fa-chart-line text-2xl text-purple-600"></i>
        </div>
        <h3 class="font-semibold text-lg">Analytics</h3>
        <p class="text-sm text-gray-500 mt-1">View sales, users, and revenue data</p>
      </a>
    </div>
  </main>
</body>
</html>
