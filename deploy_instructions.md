# Step-by-Step Production Deployment Guide

This guide walks you through deploying the **enterprise IT** application for **free** using Neon.tech, Render, and Vercel/Netlify.

---

## Part 1: Deploy the Database (Neon.tech)

**Neon.tech** offers a serverless PostgreSQL database with a generous and permanent free tier.

1. Go to [Neon.tech](https://neon.tech/) and sign up for a free account.
2. Create a new project. Select your database region (e.g. Asia Pacific or US East, whichever is closer to you).
3. Once created, copy the **Connection Details** (PostgreSQL URI). It will look similar to this:
   `postgresql://neondb_owner:xyz123@ep-glowing-snowflake-1234.ap-southeast-1.neon.tech/neondb?sslmode=require`
4. Keep these values handy. They correspond to:
   - **DB_HOST**: `ep-glowing-snowflake-1234.ap-southeast-1.neon.tech`
   - **DB_PORT**: `5432`
   - **DB_DATABASE**: `neondb`
   - **DB_USERNAME**: `neondb_owner`
   - **DB_PASSWORD**: `xyz123`
   - **DB_CONNECTION**: `pgsql`

---

## Part 2: Deploy the Backend API (Render.com)

**Render** allows you to host web applications (like Laravel) for free.

### 1. Code Preparation
*   Create a public or private repository on GitHub or GitLab containing your project files.

### 2. Create Render Web Service
1. Sign up/log in at [Render.com](https://render.com/).
2. Click **New +** and select **Web Service**.
3. Connect your GitHub/GitLab repository and select your project.
4. Set the following base configuration:
   - **Name**: `enterprise-it-backend`
   - **Language/Runtime**: `Docker`
   - **Branch**: `main`
   - **Root Directory**: `backend` (if your repository has it as a subdirectory)
   - **Build Command**: *(Leave empty — Dockerfile handles the build)*
   - **Start Command**: *(Leave empty — Dockerfile CMD handles the start)*
   - **Instance Type**: Select **Free**.

### 3. Add Environment Variables on Render
Go to the **Environment** tab in your Render service dashboard and add these keys:

| Key | Value | Purpose |
| :--- | :--- | :--- |
| `APP_ENV` | `production` | Set to production mode |
| `APP_DEBUG` | `false` | Disable error stack traces in browser |
| `APP_KEY` | `base64:your-laravel-app-key-here` | Generate locally using `php artisan key:generate` |
| `APP_URL` | `https://enterprise-it-backend.onrender.com` | Your Render service URL |
| `DB_CONNECTION` | `pgsql` | Force PostgreSQL driver |
| `DB_HOST` | *(Paste from Neon Connection Details)* | Neon DB Host |
| `DB_PORT` | `5432` | Neon DB Port |
| `DB_DATABASE` | `neondb` | Neon DB Name |
| `DB_USERNAME` | `neondb_owner` | Neon DB Username |
| `DB_PASSWORD` | *(Paste from Neon Connection Details)* | Neon DB Password |
| `ALLOWED_ORIGINS` | `https://your-frontend.vercel.app` | **CRITICAL**: Your production frontend domain |
| `SESSION_DRIVER` | `database` | Stores session info inside the Postgres DB |
| `SESSION_SECURE_COOKIE` | `true` | **CRITICAL**: Requires HTTPS for sessions |
| `SESSION_SAME_SITE` | `none` | **CRITICAL**: Permits cross-site cookie transmission |
| `SESSION_PARTITIONED_COOKIE` | `true` | **CRITICAL**: Compliance with CHIPS / Chrome cookie rules |

### 4. Database Migrations & Seeding
Once Render completes building, go to the **Shell** tab in the Render dashboard and execute:
```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force
```

---

## Part 3: Deploy the Frontend (Vercel or Netlify)

Vercel and Netlify both offer permanently free static site hosting.

### Vercel Deployment

1. Sign up/log in at [Vercel](https://vercel.com/).
2. Click **Add New** -> **Project**.
3. Select your repository.
4. Configure the project parameters:
   - **Framework Preset**: `Vite` (or `Other` / auto-detected)
   - **Root Directory**: `frontend`
   - **Build Command**: `npm run build`
   - **Output Directory**: `dist`
5. **Environment Variables**: Add a new environment variable:
   - **Key**: `VITE_API_BASE_URL`
   - **Value**: `https://enterprise-it-backend.onrender.com` (Your Render backend URL).
6. Click **Deploy**. Vercel will build and assign you a free production domain (e.g. `https://enterprise-it.vercel.app`).

### Netlify Deployment

1. Sign up/log in at [Netlify](https://www.netlify.com/).
2. Click **Add new site** -> **Import from Git**.
3. Connect your repository and select the project.
4. Set the build configurations:
   - **Base directory**: `frontend`
   - **Build command**: `npm run build`
   - **Publish directory**: `dist`
5. Under **Environment Variables**, add:
   - **Key**: `VITE_API_BASE_URL`
   - **Value**: `https://enterprise-it-backend.onrender.com`
6. Click **Deploy Site**. Netlify will host the frontend at a free `.netlify.app` domain.

---

## Part 4: Link Frontend & Backend

Once your frontend is deployed (e.g. `https://enterprise-it.vercel.app`):
1. Go back to your **Render Backend dashboard** -> **Environment** tab.
2. Edit `ALLOWED_ORIGINS` to match your actual frontend URL (e.g. `https://enterprise-it.vercel.app`).
3. Save changes. Render will automatically redeploy the backend with the correct CORS rules active.

Your application is now fully deployed, secure, and running for free!
