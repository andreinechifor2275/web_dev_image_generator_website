<?php
session_start();// Start the session to handle user login state

// Database connection setup parameters
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "user_system";
$port = 3307;

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $password, $database,$port);

// Check if connection failed
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$error = ''; // Variable to hold error messages

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and trim username and password from POST data
    $username = trim($_POST['username'] ?? '');
    $passwordInput = $_POST['password'] ?? '';

    if ($username === '' || $passwordInput === '') {
        $error = "Please fill in all fields.";
    } else {
         // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        if ($stmt) {
            // Bind the username parameter and execute the query
            $stmt->bind_param("s", $username);
            $stmt->execute();


            // Bind the result to $hashedPassword
            $stmt->bind_result($hashedPassword);

            // Check if a user was found
            if ($stmt->fetch()) {
                // Verify the entered password against the hashed password
                if (password_verify($passwordInput, $hashedPassword)) {
                    $_SESSION['username'] = $username;
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Incorrect username or password.';
                }
            } else {
                $error = 'Incorrect username or password.';
            }
            $stmt->close();
        } else {
            $error = 'Database query error.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - ArtForge</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter&display=swap" rel="stylesheet">
<style>
  html, body {
    height: 100%;
    margin: 0;
    font-family: 'Inter', sans-serif;
  }
  body {
    background: url('images/photo-1673526759327-54f1f5b27322.jpeg') no-repeat center center fixed;
    background-size: cover;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    position: relative;
  }
  body::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(124, 58, 237, 0.7);
    z-index: 0;
  }
  form {
    position: relative;
    z-index: 1;
    background-color: #1a1a1a;
    padding: 2.5rem;
    border-radius: 1.5rem;
    box-shadow: 0 10px 30px rgba(124, 58, 237, 0.5);
    width: 100%;
    max-width: 400px;
  }
  h1 {
    font-family: 'Poppins', sans-serif;
  }
  label {
    font-weight: 600;
  }
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
    outline-color: transparent;
  }
  input::placeholder {
    color: #bbb;
  }
  input:focus {
    outline-color: #7c3aed;
    background-color: #3c3c3c;
  }
  button {
    width: 100%;
    background-color: #7c3aed;
    padding: 0.75rem;
    border-radius: 1rem;
    font-weight: 600;
    font-size: 1.125rem;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
  }
  button:hover {
    background-color: #a78bfa;
  }
  .error-message {
    background-color: #b91c1c;
    color: #fca5a5;
    padding: 0.75rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
    text-align: center;
  }
  .register-link {
    margin-top: 1rem;
    text-align: center;
    font-size: 1rem;
    color: #8ddcff;
    font-weight: 600;
  }
  .register-link a {
    color: #a78bfa;
    text-decoration: underline;
  }
</style>
</head>
<body>

  <form method="POST" class="shadow-lg">
    <h1 class="text-3xl font-bold text-purple-400 mb-8 text-center">Login</h1>

    <?php if ($error): ?>
      <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <label for="username" class="block mb-2">Username</label>
    <input id="username" name="username" type="text" required
      placeholder="Username" autocomplete="username" 
      class="w-full mb-6 p-3 pl-6 rounded-xl text-black text-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />

    <label for="password" class="block mb-2">Password</label>
    <input id="password" name="password" type="password" required
      placeholder="Password" autocomplete="current-password" 
      class="w-full mb-8 p-3 pl-6 rounded-xl text-black text-lg focus:outline-none focus:ring-2 focus:ring-purple-500" />

    <button type="submit">Login</button>

    <div class="register-link">
      Don't have an account yet? <a href="register.php">Register here</a>
    </div>
  </form>

</body>
</html>
