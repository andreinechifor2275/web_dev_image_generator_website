from flask import Flask, request, jsonify, send_from_directory
from diffusers import AutoPipelineForText2Image, LCMScheduler
from flask_cors import CORS
import torch
import time
import os

app = Flask(__name__)
CORS(app)

# === Load model ONCE ===
model_path = r"C:\stable-diffusion-v1-5"
adapter_id = "latent-consistency/lcm-lora-sdv1-5"
output_dir = "images"

pipe = AutoPipelineForText2Image.from_pretrained(
    model_path,
    torch_dtype=torch.float16,
    variant="fp16"
).to("cuda")

pipe.scheduler = LCMScheduler.from_config(pipe.scheduler.config)
pipe.load_lora_weights(adapter_id)
pipe.fuse_lora()

os.makedirs(output_dir, exist_ok=True)

@app.route('/generate', methods=['POST'])
def generate():
    prompt = request.form.get('prompt', '')
    if not prompt:
        return jsonify({"success": False, "error": "Prompt is empty"}), 400

    try:
        image = pipe(prompt=prompt, num_inference_steps=6, guidance_scale=0).images[0]
        filename = f"image_{int(time.time())}.png"
        image_path = os.path.join(output_dir, filename)
        image.save(image_path)

        # Construim URL accesibil public (ngrok/local)
        image_url = request.host_url + "images/" + filename

        return jsonify({
            "success": True,
            "image": image_url
        })

    except Exception as e:
        return jsonify({"success": False, "error": str(e)}), 500

# Serve static images din folderul output_dir
@app.route('/images/<path:filename>')
def serve_image(filename):
    return send_from_directory(output_dir, filename)

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000)
