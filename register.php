<?php
session_start();

// Database connection setup
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "user_system";
$port = 3307;

$conn = new mysqli($host, $user, $password, $database, $port);
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and trim input values
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address.";
    } 
    // Check minimum length for username and password
    elseif (strlen($username) < 3 || strlen($password) < 5) {
        $message = "Username or password is too short.";
    } else {
        // Hash the password securely
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $email, $username, $hash);
            if ($stmt->execute()) {
                $message = "Account successfully created!";
            } else {
                $message = "Registration error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error preparing the query.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - ArtForge</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter&display=swap" rel="stylesheet">
  <style>
    /* Basic page and body styles */
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Inter', sans-serif;
    }
    body {
      background: url('images/photo-1673526759327-54f1f5b27322.jpeg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      position: relative;
    }
    /* Overlay to dim the background */
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(124, 58, 237, 0.7);
      z-index: 0;
    }
    /* Form container styling */
    form {
      position: relative;
      z-index: 1;
      background-color: #1a1a1a;
      padding: 2.5rem;
      border-radius: 1.5rem;
      box-shadow: 0 10px 30px rgba(124, 58, 237, 0.5);
      width: 100%;
      max-width: 400px;
      color: white;
    }
    h1 {
      font-family: 'Poppins', sans-serif;
    }
    /* Input fields styling */
    input {
      width: 100%;
      margin-bottom: 1.5rem;
      padding: 0.75rem;
      border-radius: 1rem;
      font-size: 1.125rem;
      color: white;
      background-color: #2d2d2d;
      border: none;
      outline-offset: 2px;
      text-indent: 10px;
    }
    input::placeholder {
      color: #bbb;
    }
    input:focus {
      background-color: #3c3c3c;
      outline-color: #7c3aed;
    }
    /* Button styling */
    button {
      width: 100%;
      background-color: #7c3aed;
      padding: 0.75rem;
      border-radius: 1rem;
      font-weight: 600;
      font-size: 1.125rem;
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #a78bfa;
    }
    /* Message box styling */
    .message {
      background-color: #1e40af;
      color: #93c5fd;
      padding: 0.75rem;
      border-radius: 1rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
      text-align: center;
    }
    .error {
      background-color: #b91c1c;
      color: #fca5a5;
    }
    /* Link below the form */
    .link-login {
      margin-top: 1rem;
      text-align: center;
      font-size: 0.9rem;
    }
    .link-login a {
      color: #8ddcff;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <form method="POST">
    <h1 class="text-3xl font-bold text-purple-400 mb-6 text-center">Register</h1>

    <?php if ($message): ?>
      <div class="message <?= str_starts_with(strtolower($message), 'error') ? 'error' : '' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <label for="email" class="block mb-2">Email</label>
    <input id="email" name="email" type="email" required placeholder="Email" />

    <label for="username" class="block mb-2">Username</label>
    <input id="username" name="username" type="text" required placeholder="Username" />

    <label for="password" class="block mb-2">Password</label>
    <input id="password" name="password" type="password" required placeholder="Password" />

    <button type="submit">Register</button>

    <div class="link-login mt-4">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </form>

</body>
</html>
