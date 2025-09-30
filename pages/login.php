<?php
// /auth/login.php
session_start();
require_once __DIR__ . '/../functions/db.php';
require_once __DIR__ . '/../functions/auth.php';

// If already logged in, send them to the right place
if (!empty($_SESSION['user'])) {
  if ($_SESSION['user']['role'] === 'admin') {
    header('Location: ../pages/admin_dashboard.php');
  } else {
    header('Location: ../pages/products.php');
  }
  exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(trim($_POST['email'] ?? ''));  // normalize email
  $password = $_POST['password'] ?? '';

  if ($email === '' || $password === '') {
    $error = 'Please enter email and password.';
  } else {
    $stmt = $conn->prepare("
      SELECT user_id, name, email, password, role
      FROM users
      WHERE email = ?
      LIMIT 1
    ");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
      // success: set session
      $_SESSION['user'] = [
        'id'    => (int)$user['user_id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role'],
      ];
      session_regenerate_id(true);

      // role-based redirect
      if ($user['role'] === 'admin') {
        header('Location: ../pages/admin_dashboard.php');
      } else {
        header('Location: ../pages/products.php');
      }
      exit;
    } else {
      $error = 'Invalid credentials.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <!-- Replace black box with actual logo -->
      <img src="../images/electromart2.png" alt="ElectroMart Logo" class="mx-auto w-[150px]">
      <p class="mt-6 text-2xl font-semibold text-gray-900">Sign in to your account</p>
      <p class="mt-2 text-sm text-gray-600">
        Don’t have an account?
        <a href="../pages/registration.php" class="font-semibold text-black hover:underline">Sign up</a>
      </p>
    </div>

    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold mb-4">Login</h2>

      <?php if ($error): ?>
        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-4" autocomplete="off">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
          <div class="relative">
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
                   class="w-full rounded-lg border-gray-200 px-3 py-2 focus:border-gray-400 focus:ring-0"
                   placeholder="Enter your email" required>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" name="password"
                   class="w-full rounded-lg border-gray-200 px-3 py-2 focus:border-gray-400 focus:ring-0"
                   placeholder="Enter your password" required>
          </div>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-black text-white py-2.5 font-semibold hover:opacity-90 transition">
          Sign in
        </button>
      </form>
    </div>
  </div>
</body>
</html>
