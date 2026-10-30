# Web Dev Image Generator Website

A PHP-based image generation web app that uses **Stable Diffusion v1-5** (with **LCM + LoRA**) locally via Python. It includes a login/register system, an admin CMS panel, and a user-facing AI image generator interface.

When an image is generated, it is saved in the `images/` folder, and its path is automatically added to `gallery.json` so it appears in the public gallery. User data and site settings are stored in the MySQL database.

---

## Features

- PHP-based website (compatible with XAMPP)
- MySQL database for users, site content, and settings
- Python backend using Stable Diffusion v1-5 + LCM LoRA
- Admin CMS panel to edit site content dynamically
- AI image generation from user prompt
- Image gallery loaded from a JSON file
- User login and registration system
- Generated images are saved in the `images/` folder
- Generated image info is automatically added to `gallery.json`

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)
- Python 3.10 or newer
- A GPU with CUDA support (for running Stable Diffusion)
- Git (optional, used for downloading the model)

---

## 1. XAMPP Setup

1. Install XAMPP.
2. Copy all PHP project files into the `htdocs/your_project_folder` directory.
3. Open the XAMPP Control Panel and start both **Apache** and **MySQL**.
4. Import the `cmd_db.sql` and `user_system.sql` files using **phpMyAdmin**.

---

## 2. Configuration (Important)

> [!IMPORTANT]
> Before running the site, you **must** edit `config_site.php` and update the following values according to your local or production MySQL configuration.

| Setting       | Description                     | Default (XAMPP) |
|---------------|---------------------------------|-----------------|
| Host          | MySQL server address            | `localhost`     |
| Username      | MySQL user                      | `root`          |
| Password      | MySQL password                  | *(empty)*       |
| Database name | The database you imported       | —               |
| Port          | MySQL port                      | `3306`          |

You must also update the same connection settings inside **`login.php`**. Look for the database connection section and replace the values to match those from `config_site.php`.

---

## 3. Python & Model Setup

1. Install Python 3.10 or newer.
2. Create and activate a virtual environment:

   ```bash
   python -m venv venv
   venv\Scripts\activate
   ```

3. Install dependencies:

   ```bash
   pip install -r requirements.txt
   ```

4. Download the models from Hugging Face:
   - **Stable Diffusion v1-5:** <https://huggingface.co/stable-diffusion-v1-5/stable-diffusion-v1-5>
   - **LCM LoRA adapter:** <https://huggingface.co/latent-consistency/lcm-lora-sdv1-5>

5. Place the downloaded model files inside:

   ```
   C:\stable-diffusion-v1-5
   ```

---

## 4. Running the Generator

Run:

```bat
start_generator.bat
```

---

## How It Works

1. The user enters a prompt on the website.
2. The prompt is passed to the Python backend.
3. The backend generates the image (in under 5 seconds with a supported GPU).
4. The generated image is saved in the `images/` folder inside the project.
5. The image filename/path is automatically saved to `gallery.json`.
6. The frontend gallery section loads all images from `gallery.json`.

All user information (username, password hash, etc.) and site configuration (titles, footer text, etc.) are stored in the MySQL database.

---

## Notes

- Image generation will only work if the Python backend is running properly.
- If you previously used **ngrok** and now want to run everything locally, replace the `fetch` URL in `index.php` with a relative path such as `generate.php` instead of a public link.
- If you want to deploy this project on a VPS or web server, make sure you open the correct port and point to the right Python backend.
- All PHP files can contain comments explaining their functions to improve maintainability.

---

## Support

If you need help setting up the project, make sure that:

- [ ] Apache and MySQL are running from XAMPP
- [ ] The MySQL credentials are correctly set in both `config_site.php` and `login.php`
- [ ] The Python script is correctly configured and running
- [ ] The model files are placed in the correct directory

That's all! 🎉
