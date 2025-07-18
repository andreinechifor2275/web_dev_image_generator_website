<?php
// admin.php - Simple CMS admin panel to edit site content stored in a JSON file

$contentFile = 'content.json';

// Load current content from the JSON file and decode to associative array
$content = json_decode(file_get_contents($contentFile), true);

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update content array with posted values, fallback to existing if not provided
    $content['siteTitle'] = $_POST['siteTitle'] ?? $content['siteTitle'];
    $content['heroTitle'] = $_POST['heroTitle'] ?? $content['heroTitle'];
    $content['heroText'] = $_POST['heroText'] ?? $content['heroText'];
    $content['buttonText'] = $_POST['buttonText'] ?? $content['buttonText'];
    $content['footerText'] = $_POST['footerText'] ?? $content['footerText'];

    // You can add image upload handling here if needed

    // Save updated content back to the JSON file with pretty print and UTF-8 support
    file_put_contents($contentFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - Edit Site Content</title>
</head>
<body>
  <h1>CMS - Edit Site Content</h1>

  <?php if ($success): ?>
    <!-- Success message after saving -->
    <p style="color: green;">Changes have been saved successfully.</p>
  <?php endif; ?>

  <!-- Form for editing site content -->
  <form method="post">
    <label>Site Title:<br>
      <input type="text" name="siteTitle" value="<?= htmlspecialchars($content['siteTitle']) ?>" />
    </label><br><br>

    <label>Hero Title:<br>
      <input type="text" name="heroTitle" value="<?= htmlspecialchars($content['heroTitle']) ?>" />
    </label><br><br>

    <label>Hero Text:<br>
      <textarea name="heroText" rows="5" cols="40"><?= htmlspecialchars($content['heroText']) ?></textarea>
    </label><br><br>

    <label>Hero Button Text:<br>
      <input type="text" name="buttonText" value="<?= htmlspecialchars($content['buttonText']) ?>" />
    </label><br><br>

    <label>Footer Text:<br>
      <input type="text" name="footerText" value="<?= htmlspecialchars($content['footerText']) ?>" />
    </label><br><br>

    <button type="submit">Save Changes</button>
  </form>
</body>
</html>
