# AI-Assisted Task Management System

A production-ready Task Management System built with Laravel 10+, Vue 3, Inertia.js, and Tailwind CSS. This project strictly adheres to clean architecture principles, utilizing the Repository Pattern and Service Layers to ensure maintainability and scalability.

## 🏗️ Architecture Explanation

To meet the strict requirement of **Zero direct Eloquent calls inside controllers**, this application is structured using a robust Service-Repository architecture:

* **Controllers (`TaskController.php`):** Act strictly as HTTP traffic directors. They validate incoming requests, pass data to the Service layer, and return appropriate JSON or Inertia Vue responses.
* **Service Layer (`TaskService.php`):** Encapsulates all core business logic. It orchestrates database transactions (e.g., creating a task and immediately triggering the AI summary generation) to ensure data integrity.
* **Repository Layer (`TaskRepositoryInterface.php` & `TaskRepository.php`):** Abstracts the data layer. All Eloquent queries, database interactions, and query filtering logic reside here. The application binds the interface to the Eloquent implementation via the `RepositoryServiceProvider`.
* **Security (Policies):** Authorization is handled natively via `TaskPolicy`. Administrative users have global access, while standard users are restricted to interacting solely with tasks explicitly assigned to them.

## 🤖 AI Integration Details

The AI functionality is isolated within the `AIService.php`. It handles prompt construction, API communication, and response parsing, keeping the controllers completely unaware of the AI logic.

**The Prompt Used:**
> "You are an AI assistant for a task management system. Summarize the task description and assign an AI priority (low, medium, high). Return ONLY a valid JSON object exactly like this: `{"ai_summary": "...", "ai_priority": "..."}`"

*Note: The `AIService` includes a built-in mock fallback mechanism. If the `OPENAI_API_KEY` is not provided or the API limit is reached, it will gracefully return a simulated JSON response to prevent application crashes.*

## 🚀 Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <your-repository-url>
   cd task-manager

2. **Install PHP and Node dependencies**
composer install
npm install

3. **Environment Setup**
cp .env.example .env
php artisan key:generate
(Configure MySQL database credentials and add your OPENAI_API_KEY in the .env file.)

4. **Run Migrations and Seeders**
php artisan migrate --seed
(Seeder creates two test accounts: admin@test.com and user@test.com. Password: password)

5. **Build Assets and Serve**
npm run build
php artisan serve

