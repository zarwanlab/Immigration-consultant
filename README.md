# AI-Powered Immigration Consultant Tool

A modern, pixel-perfect, and database-less web application designed to help users assess their immigration eligibility using advanced AI models. Built with a focus on speed, privacy, and exceptional user experience.

## 🚀 Key Features

- **AI-Driven Assessment**: Leverages advanced LLMs to provide professional immigration advice and "Plan B" alternatives.
- **Zero-Build Architecture**: Pure HTML/CSS/JS frontend using CDNs (No NPM/Vite required for deployment).
- **Glassmorphism Design**: High-end UI with backdrop blurs, soft shadows, and smooth animations.
- **Multilingual Support**: Full support for English, Persian (Farsi), and Arabic with seamless RTL/LTR switching.
- **Database-less History**: Saves assessment history directly to local files for privacy and portability.
- **Single Page Experience**: Entire process from questionnaire to results happens on a single, fluid page.
- **Export Options**: Users can copy results to clipboard or download them as a structured text report.

## 🛠 Prerequisites

- **PHP**: ^8.3
- **Composer**: Latest version
- **AI API Key**: Access to a compatible OpenAI-style API (configured in `.env`)

## 📥 Installation

Follow these steps to set up the project locally:

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd Immigration-consultant
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   Copy the example environment file and set your AI credentials:
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and fill in:
   - `AI_BASE_URL`
   - `AI_TOKEN`
   - `AI_MODEL`

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Start the Server**:
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to start your assessment.

## 📁 Project Structure

- `app/Http/Controllers/EligibilityController.php`: Handles AI logic and file history.
- `resources/views/welcome.blade.php`: The main SPA interface.
- `lang/`: Contains JSON translation files for EN, FA, and AR.
- `storage/app/eligibility_history.json`: Local storage for assessments.

## ⚖️ Disclaimer

This tool provides a preliminary assessment based on AI analysis. Final decisions and official advice should always be sought from certified immigration specialists.

---
Made with ❤️ by **HERD AI**
