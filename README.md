Install:
1. Make sure php and sql(mysql, pgsql,...) have already been installed in your local machine
2. Create a .env file with your database configuration, see .env.example in the repository as an example
3. Run: composer install
4. Run: npm install

Run the app:
1. Run: php artisan serve --port=8000
2. Route list:

. GET: localhost:8000/api/todos ---param: pageSize=9, color=[green,blue,orange,purple,red], status=all|active|completed|deleted

. POST: localhost:8000/api/todos

. GET: localhost:8000/api/todos/mark-completed ---param: ids=[1,2,3]

. GET: localhost:8000/api/todos/clear-completed ---param: ids=[1,2,3]

. GET: localhost:8000/api/todos/{id}

. PUT: localhost:8000/api/todos/{id} ---param: text=..., color=green|blue|orange|purple|red

. DELETE: localhost:8000/api/todos/{id}
