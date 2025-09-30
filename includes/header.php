<?php
if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Compute cart item count safely from session.
 */
$cartCount = 0;

// From session (supports multiple shapes)
$sessionCart = $_SESSION['cart'] ?? null;
if (is_array($sessionCart)) {
  $items = isset($sessionCart['items']) && is_array($sessionCart['items'])
           ? $sessionCart['items']
           : $sessionCart;

  foreach ($items as $it) {
    if (is_array($it)) {
      if (isset($it['qty']))          $cartCount += (int)$it['qty'];
      elseif (isset($it['quantity'])) $cartCount += (int)$it['quantity'];
      else                             $cartCount += 1;
    } else {
      $cartCount += 1;
    }
  }
}

// Override from DB if you keep a cart table per user
try {
  if (!empty($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
    $userId = (int)$_SESSION['user']['user_id'];
    $dbPath = __DIR__ . '/../functions/db.php';
    if (file_exists($dbPath)) {
      require_once $dbPath; // expects $conn (mysqli)
      if (isset($conn) && $conn instanceof mysqli) {
        // Adjust table/columns to match your schema
        $sql = "SELECT COALESCE(SUM(quantity), 0) AS c FROM cart_items WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
          $stmt->bind_param('i', $userId);
          $stmt->execute();
          $res = $stmt->get_result();
          if ($res) {
            $row = $res->fetch_assoc();
            if ($row && isset($row['c'])) {
              $dbCount = (int)$row['c'];
              if ($dbCount > 0 || $cartCount === 0) {
                $cartCount = $dbCount;
              }
            }
          }
          $stmt->close();
        }
      }
    }
  }
} catch (Throwable $e) {
  // swallow errors to avoid breaking header
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ElectroMart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="min-h-screen flex flex-col bg-gray-50">

  <!-- Navbar -->
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

      <!-- Logo -->
      <a href="../pages/index.php" class="flex items-center text-xl font-bold text-gray-900">
        <img src="../images/electromart2.png" alt="ElectroMart Logo" class="w-[110px] md:w-[130px]">
      </a>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center space-x-10">
        <a href="../pages/index.php" class="text-gray-700 hover:text-black">Home</a>
        <a href="../pages/products.php" class="text-gray-700 hover:text-black">Products</a>
        <a href="../pages/about.php" class="text-gray-700 hover:text-black">About</a>
      </nav>

      <!-- Right Side -->
      <div class="flex items-center gap-4">

        <!-- Desktop Search -->
        <form action="../pages/search.php" method="GET" class="hidden md:flex">
          <input type="text" name="query" placeholder="Search products..."
                 class="border rounded-l px-3 py-1 w-48 lg:w-64 focus:outline-none text-sm" required>
          <button type="submit"
                  class="bg-black text-white px-3 lg:px-4 py-1 rounded-r hover:bg-gray-900 transition text-sm">
            Search
          </button>
        </form>

        <!-- Auth / User -->
        <?php if (!empty($_SESSION['user'])): ?>
          <span class="hidden md:block text-gray-700 text-sm">
            Hi, <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong>
          </span>

          <!-- ✅ Added My Purchases for desktop -->
          <a href="../pages/purchases.php" class="hidden md:block text-gray-700 hover:text-black text-sm">
            My Purchases
          </a>

          <a href="../pages/logout.php" class="hidden md:block text-gray-500 hover:text-black text-sm">Logout</a>

          <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
            <a href="../pages/admin_dashboard.php" class="hidden md:block text-sm font-semibold text-red-600">Admin Panel</a>
          <?php endif; ?>
        <?php else: ?>
          <a href="../pages/login.php" class="hidden md:block text-gray-700 hover:text-black">Login</a>
        <?php endif; ?>

        <!-- Cart -->
        <a href="../pages/cart.php" class="relative">
          <i class="fa-solid fa-cart-shopping"></i>
          <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1">
            <?= (int)$cartCount ?>
          </span>
        </a>

        <!-- Mobile Menu Button -->
        <button id="menuBtn" class="md:hidden text-2xl focus:outline-none">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="mobileMenu" class="md:hidden hidden border-t bg-white px-4 pb-4 space-y-3">
      <form action="../pages/search.php" method="GET" class="flex">
        <input type="text" name="query" placeholder="Search..."
               class="border rounded-l px-3 py-2 w-full focus:outline-none" required>
        <button type="submit"
                class="bg-black text-white px-4 py-2 rounded-r hover:bg-gray-900 transition">
          Go
        </button>
      </form>

      <a href="../pages/index.php" class="block py-2 text-gray-700 hover:text-black">Home</a>
      <a href="../pages/products.php" class="block py-2 text-gray-700 hover:text-black">Products</a>
      <a href="../pages/about.php" class="block py-2 text-gray-700 hover:text-black">About</a>

      <?php if (!empty($_SESSION['user'])): ?>
        <a href="../pages/purchases.php" class="block py-2 text-gray-700 hover:text-black">My Purchases</a>
        <a href="../pages/logout.php" class="block py-2 text-gray-700 hover:text-black">Logout</a>
        <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
          <a href="../pages/admin_dashboard.php" class="block py-2 text-red-600 font-semibold">Admin Panel</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="../pages/login.php" class="block py-2 text-gray-700 hover:text-black">Login</a>
      <?php endif; ?>
    </div>
  </header>

  <main class="flex-1">
