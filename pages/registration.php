<?php
// /pages/registration.php
session_start();
require_once __DIR__ . '/../functions/db.php';

// Already logged in? Send to products.
if (!empty($_SESSION['user'])) {
  header('Location: ../pages/products.php');
  exit;
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get + normalize
  $name     = trim($_POST['name'] ?? '');
  $email    = strtolower(trim($_POST['email'] ?? ''));
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm'] ?? '';

  // Validate
  if ($name === '' || mb_strlen($name) < 2) {
    $errors[] = 'Please enter your full name (at least 2 characters).';
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
  }
  if (mb_strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters long.';
  }
  if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
  }

  // If still OK, ensure email is unique
  if (!$errors) {
    $stmt = $conn->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $errors[] = 'That email is already registered. Try signing in.';
    }
    $stmt->close();
  }

  // Insert user
  if (!$errors) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("
      INSERT INTO users (name, email, password, role)
      VALUES (?, ?, ?, 'user')
    ");
    $stmt->bind_param('sss', $name, $email, $hash);
    if ($stmt->execute()) {
      // Auto-login new user
      $_SESSION['user'] = [
        'id'    => (int)$stmt->insert_id,
        'name'  => $name,
        'email' => $email,
        'role'  => 'user',
      ];
      $stmt->close();
      header('Location: ../pages/products.php');
      exit;
    } else {
      $errors[] = 'Something went wrong creating your account. Please try again.';
      $stmt->close();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create your account - ElectroMart</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
  <div class="w-full max-w-md">
    <!-- Brand -->
    <div class="text-center mb-8">
      <!-- Use actual logo instead of black box -->
      <img src="../images/electromart2.png" alt="ElectroMart Logo" class="mx-auto w-[150px]">
      <p class="mt-6 text-2xl font-semibold text-gray-900">Create your account</p>
      <p class="mt-2 text-sm text-gray-600">
        Already have an account?
        <a href="../pages/login.php" class="font-semibold text-black hover:underline">Sign in</a>
      </p>
    </div>

    <!-- Card -->
    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold mb-4">Register</h2>

      <?php if ($errors): ?>
        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
          <ul class="list-disc pl-5 space-y-1">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-4" autocomplete="off">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <div class="relative">
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"
                   class="w-full rounded-lg border-gray-200 px-3 py-2 focus:border-gray-400 focus:ring-0"
                   placeholder="Enter your full name" required>
          </div>
        </div>

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
          <p class="mt-1 text-xs text-gray-500">Minimum 8 characters.</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <div class="relative">
            <input type="password" name="confirm"
                   class="w-full rounded-lg border-gray-200 px-3 py-2 focus:border-gray-400 focus:ring-0"
                   placeholder="Confirm your password" required>
          </div>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-black text-white py-2.5 font-semibold hover:opacity-90 transition">
          Create account
        </button>
      </form>
    </div>
  </div>
</body>
</html>
