<?php
session_start();
require 'config_site.php';

// Fetch site settings from the database
$query = "SELECT * FROM site_settings WHERE id=1";
$result = $conn->query($query);
if (!$result || $result->num_rows === 0) {
    die('Error: Site settings not found.');
}
$content = $result->fetch_assoc();

// Use fallback values
$siteTitle = $content['siteTitle'] ?? 'ArtForge';
$heroTitle = $content['heroTitle'] ?? 'AI Image Generator';
$heroText = $content['heroText'] ?? 'Create amazing images with AI';
$buttonText = $content['buttonText'] ?? 'Generate Now';
$footerText = $content['footerText'] ?? '© 2025 ArtForge. All rights reserved.';

// Fetch gallery images from the database
$gallery = [];
$gallery_result = $conn->query("SELECT image_url FROM gallery");
if ($gallery_result && $gallery_result->num_rows > 0) {
    while ($row = $gallery_result->fetch_assoc()) {
        $gallery[] = $row['image_url'];
    }
}

// JS variable to track if the user is logged in or not
$isLoggedIn = isset($_SESSION['username']) ? 'true' : 'false';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($siteTitle) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter&display=swap" rel="stylesheet" />
  <style>
    /* Background animation with gradient colors */
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(-45deg, #1a001f, #0c0c0c, #1e0033, #000000);
      background-size: 400% 400%;
      animation: gradientBG 20s ease infinite;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    /* Headings font style */
    h1, h2, h3 {
      font-family: 'Poppins', sans-serif;
    }
     /* Custom glow button styles */
    .glow-button {
      background: linear-gradient(90deg, #7e22ce, #9333ea);
      color: white;
      border: none;
      box-shadow: 0 0 15px #9333ea99;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .glow-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 25px #a855f799;
    }
  </style>
</head>
<body class="text-white">

  <!-- Navbar -->
  <nav class="flex items-center p-6 border-b border-purple-900 bg-black/40 backdrop-blur-md">
    <h1 class="text-2xl font-bold text-purple-400"><?= htmlspecialchars($siteTitle) ?></h1>
    <div class="flex items-center gap-4 ml-auto">
      <?php if (isset($_SESSION['username'])): ?>
        <span class="text-gray-300">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>

        <?php if ($_SESSION['username'] === 'admin'): ?>
          <a href="admin_settings.php" class="glow-button px-4 py-2 rounded-xl text-sm">CMS</a>
        <?php endif; ?>

        <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl transition text-sm">Logout</a>
      <?php else: ?>
        <a href="login.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition text-sm">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="text-center py-24 px-4">
    <h2 class="text-5xl font-bold mb-4 text-purple-300"><?= htmlspecialchars($heroTitle) ?></h2>
    <p class="text-xl text-gray-300 mb-10"><?= htmlspecialchars($heroText) ?></p>
    <button id="startButton" class="glow-button px-8 py-4 rounded-full text-lg font-semibold">
      <?= htmlspecialchars($buttonText) ?>
    </button>
  </section>

  <!-- Gallery -->
  <section class="px-6 py-16 bg-black/30 rounded-3xl mx-4 mb-10 backdrop-blur-md">
    <h3 class="text-3xl font-semibold mb-8 text-purple-300 text-center">Gallery Showcase</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($gallery as $imgSrc): ?>
        <img src="<?= htmlspecialchars($imgSrc) ?>" class="rounded-xl shadow-lg hover:scale-105 transition-transform duration-300" alt="Generated Image" />
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Generator -->
  <section id="generateSection" class="px-6 py-20 bg-[#0a0a0a]/80 rounded-t-3xl shadow-2xl backdrop-blur-md">
    <h3 class="text-3xl font-semibold mb-6 text-center text-purple-200">Generate a New Image</h3>
    <form id="generateForm" class="flex flex-col md:flex-row gap-4 justify-center max-w-4xl mx-auto">
      <input id="promptInput" type="text" placeholder="Enter a prompt..." class="flex-1 p-4 rounded-xl text-black text-lg focus:outline-purple-500" required />
      <button type="submit" class="glow-button px-6 py-3 rounded-xl text-lg font-medium">Generate</button>
    </form>
    <div id="generatedImage" class="mt-10 text-center"></div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-6 text-gray-400 text-sm mt-10 bg-black/50">
    <?= htmlspecialchars($footerText) ?>
  </footer>

  <script>
    // JS variable to check if the user is logged in (true/false)
    const isLoggedIn = <?= $isLoggedIn ?>;

    // Handle the form submission to generate an image
    document.getElementById('generateForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      if (!isLoggedIn) {
         // Redirect to login page if user is not logged in
        window.location.href = 'login.php';
        return;
      }

      // Get user input prompt
      const prompt = document.getElementById('promptInput').value;
      const formData = new FormData();
      formData.append('prompt', prompt);

      // Show loading message while waiting for image generation
      const output = document.getElementById('generatedImage');
      output.innerHTML = `<p class="text-purple-300">Generating image...</p>`;

      try {
         // Call the AI generation API endpoint (replace with your ngrok or server URL)
        const response = await fetch('http://localhost:5000/generate', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          // Display generated image
          output.innerHTML = `
            <p class="text-green-400 mb-2">Generated Image:</p>
            <img src="${result.image}" alt="Generated Image" class="w-64 h-64 object-cover rounded-xl shadow-lg mx-auto">
          `;
        } else {
          // Show error message returned from server
          output.innerHTML = `<p class="text-red-500">${result.error}</p>`;
        }

      } catch (err) {
        // Show generic server error message on failure
        output.innerHTML = `<p class="text-red-500">Server error: ${err.message}</p>`;
      }
    });

    // Scroll down to generator form when clicking the hero button
    document.getElementById('startButton').addEventListener('click', function() {
      document.getElementById('generateSection').scrollIntoView({ behavior: 'smooth' });
    });
  </script>
</body>
</html>
