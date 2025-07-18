<?php
session_start();

// Not allowed if username is not 'admin'
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'config_site.php';

$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteTitle = $_POST['siteTitle'] ?? '';
    $heroText = $_POST['heroText'] ?? '';
    $buttonText = $_POST['buttonText'] ?? '';
    $footerText = $_POST['footerText'] ?? '';

    $stmt = $conn->prepare("UPDATE site_settings SET siteTitle=?, heroText=?, buttonText=?, footerText=? WHERE id=1");
    if (!$stmt) {
        die("SQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $siteTitle, $heroText, $buttonText, $footerText);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: admin_settings.php?saved=1");
    exit;
}

// Show success message if redirected after saving
if (isset($_GET['saved'])) {
    $success = "Settings have been saved!";
}

// Fetch current settings
$result = $conn->query("SELECT * FROM site_settings WHERE id=1");
$settings = $result ? $result->fetch_assoc() : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-md">

    <!-- Back button -->
    <div class="mb-6">
      <a href="index.php" class="inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">&larr; Back</a>
    </div>

    <h1 class="text-2xl font-bold mb-4">Admin Panel</h1>

    <?php if ($success): ?>
      <p class="mb-4 text-green-600 font-semibold"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block font-semibold">Site Title</label>
        <input type="text" name="siteTitle" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings['siteTitle'] ?? '') ?>">
      </div>
      <div>
        <label class="block font-semibold">Hero Text</label>
        <input type="text" name="heroText" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings['heroText'] ?? '') ?>">
      </div>
      <div>
        <label class="block font-semibold">Button Text</label>
        <input type="text" name="buttonText" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings['buttonText'] ?? '') ?>">
      </div>
      <div>
        <label class="block font-semibold">Footer Text</label>
        <input type="text" name="footerText" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings['footerText'] ?? '') ?>">
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
    </form>
  </div>
</body>
</html>
