## Kanban Board

A Kanban app built using Laravel and Vue.js.
This app allows users to create and manage their tasks using a kanban board.

### Features
- User can create and manage tasks on a kanban board.
- Tasks can be moved to different columns and in different order
- User can remove card
- User can remove whole column
- User can export database

### Requirements
- PHP 8.0 or above
- MySQL or PostgreSQL
- Composer
- npm

### Installation

1. Clone the repository: `git clone git@github.com:kavanpancholi/kanban.git`
2. Install dependencies: `composer install` and `npm install`
3. Create a new database and update the .env file with the database credentials.
4. Make sure to add `VITE_APP_URL` with value of the path of the app in server
5. Generate app key: `php artisan key:generate`
6. Run migrations & seeds: `php artisan migrate:fresh --seed`
7. Compile assets: `npm run build`
8. Open browser and enter project url

---

**Author:**
- Kavan Pancholi
- kavanpancholi@gmail.com
- https://github.com/kavanpancholi
- https://www.linkedin.com/in/kavanpancholi/
