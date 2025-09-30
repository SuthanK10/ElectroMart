<?php
// /pages/analytics.php
session_start();
require_once __DIR__ . '/../functions/db.php';

// Admin gate
if (empty($_SESSION['user']) || (($_SESSION['user']['role'] ?? '') !== 'admin')) {
  header('Location: ../pages/login.php');
  exit;
}

// Which view?
$view = $_GET['view'] ?? 'orders';
$view = in_array($view, ['orders','users']) ? $view : 'orders';

// -------- Orders query (existing) --------
$orders = [];
if ($view === 'orders') {
  $sql = "
    SELECT 
      o.order_id,
      o.total        AS order_total,
      o.created_at,
      u.name         AS customer_name,
      u.email,
      p.name         AS product_name,
      oi.quantity,
      oi.price
    FROM orders o
    JOIN users u       ON o.user_id = u.user_id
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p     ON oi.product_id = p.product_id
    ORDER BY o.created_at ASC
  ";
  if ($res = $conn->query($sql)) {
    $orders = $res->fetch_all(MYSQLI_ASSOC);
  }
}

// -------- Users query (new) --------
$users = [];
$usersCount = 0;
if ($view === 'users') {
  // Add/adjust columns based on your schema (e.g., created_at may be NULL)
  $sqlUsers = "
    SELECT 
      user_id, 
      name, 
      email, 
      role,
      created_at
    FROM users
    ORDER BY created_at ASC, user_id ASC
  ";
  if ($res = $conn->query($sqlUsers)) {
    $users = $res->fetch_all(MYSQLI_ASSOC);
    $usersCount = count($users);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Analytics - ElectroMart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-6xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-4">Analytics</h1>

    <!-- Top bar -->
    <div class="mb-6 flex items-center justify-between">
      <a href="../pages/admin_dashboard.php"
         class="inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm bg-white hover:bg-gray-50 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Dashboard
      </a>

      <!-- Tabs -->
      <div class="inline-flex rounded-lg overflow-hidden border bg-white shadow-sm">
        <a href="?view=orders"
           class="px-4 py-2 text-sm <?= $view==='orders' ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
          Orders
        </a>
        <a href="?view=users"
           class="px-4 py-2 text-sm <?= $view==='users' ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
          Users
          <?php if ($view==='users'): ?>
            <span class="ml-2 inline-flex items-center justify-center rounded-full bg-gray-200 px-2 text-xs text-gray-700">
              <?= (int)$usersCount ?>
            </span>
          <?php endif; ?>
        </a>
      </div>
    </div>

    <!-- Content card -->
    <div class="rounded-xl border bg-white shadow-sm p-6 overflow-x-auto">
      <?php if ($view === 'orders'): ?>
        <!-- ORDERS TABLE -->
        <table class="w-full text-left border-collapse min-w-[900px]">
          <thead>
            <tr class="border-b bg-gray-100">
              <th class="p-3">Order ID</th>
              <th class="p-3">Customer</th>
              <th class="p-3">Product</th>
              <th class="p-3">Qty</th>
              <th class="p-3">Price</th>
              <th class="p-3">Line Total</th>
              <th class="p-3">Order Total</th>
              <th class="p-3">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $row): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="p-3"><?= (int)$row['order_id'] ?></td>
                <td class="p-3">
                  <?= htmlspecialchars($row['customer_name']) ?><br>
                  <span class="text-xs text-gray-500"><?= htmlspecialchars($row['email']) ?></span>
                </td>
                <td class="p-3"><?= htmlspecialchars($row['product_name']) ?></td>
                <td class="p-3"><?= (int)$row['quantity'] ?></td>
                <td class="p-3">$<?= number_format((float)$row['price'], 2) ?></td>
                <td class="p-3">$<?= number_format((int)$row['quantity'] * (float)$row['price'], 2) ?></td>
                <td class="p-3">$<?= number_format((float)($row['order_total'] ?? 0), 2) ?></td>
                <td class="p-3"><?= $row['created_at'] ? date('Y-m-d H:i', strtotime($row['created_at'])) : '-' ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
              <tr><td colspan="8" class="p-6 text-center text-gray-500">No orders found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>

      <?php else: ?>
        <!-- USERS TABLE -->
        <table class="w-full text-left border-collapse min-w-[720px]">
          <thead>
            <tr class="border-b bg-gray-100">
              <th class="p-3">User ID</th>
              <th class="p-3">Name</th>
              <th class="p-3">Email</th>
              <th class="p-3">Role</th>
              <th class="p-3">Registered</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="p-3"><?= (int)$u['user_id'] ?></td>
                <td class="p-3"><?= htmlspecialchars($u['name']) ?></td>
                <td class="p-3"><?= htmlspecialchars($u['email']) ?></td>
                <td class="p-3">
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs
                    <?= ($u['role'] ?? '') === 'admin' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' ?>">
                    <?= htmlspecialchars($u['role'] ?? 'user') ?>
                  </span>
                </td>
                <td class="p-3">
                  <?= !empty($u['created_at']) ? date('Y-m-d H:i', strtotime($u['created_at'])) : '-' ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
              <tr><td colspan="5" class="p-6 text-center text-gray-500">No users found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
