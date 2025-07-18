Web Dev Image Generator Website

This is a PHP-based image generation web app that uses Stable Diffusion v1-5 (with LCM + LoRA) locally via Python. It includes a login/register system, an admin CMS panel, and a user-facing AI image generator interface. When an image is generated, it is saved in the "images" folder, and its path is automatically added to "gallery.json" so it appears in the public gallery. User data and site settings are stored in the MySQL database.

--

FEATURES

- PHP-based website (compatible with XAMPP)
- MySQL database for users, site content, and settings
- Python backend using Stable Diffusion v1-5 + LCM LoRA
- Admin CMS panel to edit site content dynamically
- AI image generation from user prompt
- Image gallery loaded from JSON file
- User login and registration system
- Generated images are saved in the "images" folder
- Generated image info is automatically added to "gallery.json"

--

REQUIREMENTS

- XAMPP (Apache + MySQL)
- Python 3.10 or newer
- A GPU with CUDA support (for running Stable Diffusion)
- Git (optional, used for downloading the model)

--

1. XAMPP SETUP

1. Install XAMPP.
2. Copy all PHP project files into the `htdocs/your_project_folder` directory.
3. Open XAMPP Control Panel and start both **Apache** and **MySQL**.
4. Import the `cmd_db.sql` and 'user_system.sql' files using **phpMyAdmin**.

--

2. CONFIGURATION (IMPORTANT)

Before running the site, you MUST edit the file called `config_site.php` and update the following values according to your local or production MySQL configuration:

- host (usually "localhost")
- username (default is "root")
- password (default is empty on XAMPP)
- database name (the one you imported)
- port (default MySQL port is 3306, but change if necessary)

You must also update the same connection settings inside the `login.php` file. Look for the database connection section and replace the values to match those from `config_site.php`.

--

3. PYTHON & MODEL SETUP

1. Install Python 3.10 or newer.
2. Create a virtual environment:
   python -m venv venv
   venv\Scripts\activate
3. Install dependencies:
   pip install -r requirements.txt
4. Download models from Hugging Face:
   - Stable Diffusion v1-5: https://huggingface.co/stable-diffusion-v1-5/stable-diffusion-v1-5
   - LCM LoRA adapter: https://huggingface.co/latent-consistency/lcm-lora-sdv1-5
5. Place your downloaded model files inside:
   C:\stable-diffusion-v1-5

--

4. RUNNING THE GENERATOR

Run: start_generator.bat

--

NOTES

- The image generation will only work if the Python backend is running properly.
- The image generation process takes a prompt from the user, passes it to the Python backend, and returns a generated image in under 5 seconds (with a supported GPU).
- If you previously used ngrok and now want to run everything locally, just replace the `fetch` URL in `index.php` with a relative path like `generate.php` instead of a public link.
- If you want to deploy this project on a VPS or web server, make sure you open the correct port and point to the right Python backend.
- All PHP files can contain comments to explain the functions if you want to improve maintainability.
- The image generation process takes a prompt from the user, passes it to the Python backend, and returns a generated image.
- The generated image is saved in the "images/" folder inside your project.
- After generation, the image filename/path is automatically saved to "gallery.json".
- The frontend gallery section loads all images from gallery.json.
- All user information (username, password hash, etc.) and site configuration (titles, footer text, etc.) are stored in the MySQL database.

--

SUPPORT

If you need help setting up the project, make sure you have:
- Apache and MySQL running from XAMPP
- The MySQL credentials correctly set in both config_site.php and login.php
- The Python script correctly configured and running
- The model files placed in the correct directory

That's all!
