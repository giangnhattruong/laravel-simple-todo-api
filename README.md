### Install:
1. Make sure php, composer, nodejs and sql(mysql, pgsql,...) have already been installed in your local machine
2. Create a .env file with your database configuration, see .env.example in the repository as an example (just copy & paste & config the database)
3. Run: ```composer install```
4. Run: ```npm install```
5. Database & seed: ```php artisan migrate --seed```

### Run the app:
1. Run: php artisan serve --port=8000
2. Route list:

- GET: [localhost:8000/api/todos](http://localhost:8000/api/todos) ---param(optional): pageSize=9, color=[green,blue,orange,purple,red], status=all|active|completed|deleted
- POST: [localhost:8000/api/todos](http://localhost:8000/api/todos) ---param: text="Feed my cat"
- GET: [localhost:8000/api/todos/mark-completed](http://localhost:8000/api/todos/mark-completed) ---param(optional): ids=[1,2,3]
- GET: [localhost:8000/api/todos/clear-completed](http://localhost:8000/api/todos/clear-completed) ---param(optional): ids=[1,2,3]
- GET: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
- PUT: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1) ---param: text="Go to sleep", color=green|blue|orange|purple|red, completed=true|false
- DELETE: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
