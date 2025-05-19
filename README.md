# StreamHive - TMDB Movie Explorer

## Overview

**StreamHive** is a Laravel-based movie listing application built using The Movie Database (TMDB) API. It allows users to view popular movies, save favorites, and contact the developer. This app was created as part of a professional coding assessment by **Aglet Interactive**

The application is responsive and styled using **Tailwind CSS**. It includes a working **authentication system**, **favorites feature**, **search with autocomplete**, **movie detail modals**, and a **contact page**.

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Blade, TailwindCSS
- **Database:** MySQL
- **API:** The Movie Database (TMDB)
- **Authentication:** Laravel Breeze
- **Search:** AJAX with TMDB autocomplete endpoint

---

## Why Laravel (PHP)?

I chose **Laravel** as the backend framework due to its elegant syntax, powerful built-in features, and robust ecosystem. It simplifies common tasks such as:

- Database management via Eloquent ORM
- Route handling and middleware
- Authentication
- API integration

Using Laravel allowed me to rapidly scaffold out the required features while keeping the codebase organized and testable.

---

## Why TailwindCSS?

TailwindCSS enables utility-first styling, which is perfect for rapidly developing a responsive and visually appealing frontend. It also makes it easy to maintain consistent spacing, colors, and responsive breakpoints.

---

## My Thinking

- **User-first approach:** I ensured all views are intuitive and mobile-friendly.
- **Code maintainability:** Separation of concerns via models, controllers, views, and services.
- **Scalability:** TMDB integration is isolated so it can be easily extended or replaced.

---

## Features

- Fetch and display movies from TMDB (paginated, 9 per page, up to 45 total)
- View movie details and trailer
- Save favorite movies (requires login)
- Predefined test user login:
  - **Username:** `jointheteam`
  - **Email:** `jointheteam@aglet.co.za`
  - **Password:** `@TeamAglet`
- View favorite movies
- Responsive layout
- Contact page with developer info
- Clean UI

---

## Brownie Points Achieved

- Movie search with autocomplete using TMDB search endpoint
- Movie detail popup modals with extended info (title, overview, trailer)
- Responsive and professional frontend UI
- Converted into a WordPress plugin: [StreamHive Plugin (Attempt)](https://github.com/MfundoM/stream-hive-plugin)

---

## Installation Guide

1. **Clone the repository**

   ```bash
   git clone https://github.com/MfundoM/stream-hive.git
   cd stream-hive

   # Install dependencies
    composer install

    # Create environment file
    cp .env.example .env

    # Generate app key
    php artisan key:generate

    # Configure your .env with DB credentials and tmdb api-key
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    TMDB_API_KEY=your_tmdb_api_key

    # Run migrations
    php artisan migrate --seed

    # Start the dev server
    php artisan serve
    npm install && npm run dev
