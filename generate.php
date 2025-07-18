<?php
// Full path to the Python executable
$python = 'C:\\Users\\andre\\AppData\\Local\\Programs\\Python\\Python310\\python.exe';

// Get the prompt sent via POST request
$prompt = $_POST['prompt'] ?? '';

if (!$prompt) {
    // Return error if prompt is empty
    echo json_encode(['success' => false, 'error' => 'Prompt is empty']);
    exit;
}

// Command to run the Python script that generates the image
$cmd = "$python generate_image.py";

// Setup descriptors for process pipes:
// 0 => STDIN (write to the process)
// 1 => STDOUT (read from the process)
// 2 => STDERR (read errors from the process)
$descriptorspec = [
    0 => ['pipe', 'r'],  // STDIN for the process
    1 => ['pipe', 'w'],  // STDOUT for the process
    2 => ['pipe', 'w']   // STDERR for the process
];

// Start the Python process
$process = proc_open($cmd, $descriptorspec, $pipes);

if (!is_resource($process)) {
    // If process didn't start, return an error
    echo json_encode(['success' => false, 'error' => 'Failed to start python process']);
    exit;
}

// Write the prompt to the Python script's STDIN
fwrite($pipes[0], "prompt=$prompt\n");
fclose($pipes[0]);

// Read the output from the Python script (expected to be JSON)
$output = stream_get_contents($pipes[1]);
fclose($pipes[1]);

// Read any error output from the Python script
$error_output = stream_get_contents($pipes[2]);
fclose($pipes[2]);

// Close the process and get its return value
$return_value = proc_close($process);

if ($return_value !== 0) {
    // If Python script returned an error code, send error message back
    echo json_encode(['success' => false, 'error' => 'Python error: ' . $error_output]);
    exit;
}

// Output the JSON response from the Python script directly to the client
echo $output;
?>
