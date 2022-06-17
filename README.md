Install:
1. Make sure php and sql(mysql, pgsql,...) have already been installed in your local machine
2. Create a .env file with your database configuration, see .env.example in the repository for more infomation
3. Run: composer install
4. Run: npm install

Run the app:
1. Run: php artisan serve --port=8000
2. Route list:
. GET: localhost:8000/api/todos?text=my-todo&status=all&color=[red,green]
. POST: localhost:8000/api/todos
. GET: localhost:8000/api/todos/mark-completed?ids=[1,2,3]
. GET: localhost:8000/api/todos/clear-completed?ids=[1,2,3]
. GET|PUT|DELETE: localhost:8000/api/todos/{id}
