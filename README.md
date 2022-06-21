<!-- ### Install:
1. Make sure php, composer, nodejs and sql(mysql, pgsql,...) have already been installed in your local machine
2. Create a .env file with your database configuration, see .env.example in the repository as an example (just copy & paste & config the database)
3. Run: ```composer install```
4. Run: ```npm install```
5. Database & seed: ```php artisan migrate --seed```

### Run the app:
1. Run: ```php artisan serve --port=8000```
2. Route list:

- GET: [localhost:8000/api/todos](http://localhost:8000/api/todos) ---params (optional): pageSize=9, color=[green,blue,orange,purple,red], status=all|active|completed|deleted
- POST: [localhost:8000/api/todos](http://localhost:8000/api/todos) ---param: text="Feed my cat"
- GET: [localhost:8000/api/todos/mark-completed](http://localhost:8000/api/todos/mark-completed) ---param (optional): ids=[1,2,3]
- GET: [localhost:8000/api/todos/clear-completed](http://localhost:8000/api/todos/clear-completed) ---param (optional): ids=[1,2,3]
- GET: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
- PUT: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1) ---params (optional): text="Go to sleep", color=green|blue|orange|purple|red, completed=true|false
- DELETE: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)

#API link: [GitHub](https://github.com/giangnhattruong/laravel-simple-todo-api) -->
## Cài đặt:
1. Trước tiên cần cài đặt php, composer, nodejs và sql(mysql, pgsql,...)
2. Tạo một file .env (copy từ file .env.example)
3. Config database tại file .env này
3. Chạy command: ```composer install```
4. Chạy command: ```npm install```
5. Database & seed: ```php artisan migrate --seed```
6. Chạy command: ```php artisan serve --port=8000```

## Todo route list
- GET: [localhost:8000/api/todos](http://localhost:8000/api/todos)
    - params (optional): pageSize=9, color=[green,blue,orange,purple,red], status=all|active|completed|deleted, sortBy=dateDesc|dateAsc|nameDesc|nameAsc
- POST: [localhost:8000/api/todos](http://localhost:8000/api/todos)
    - param: text="Feed my cat"
- GET: [localhost:8000/api/todos/mark-completed](http://localhost:8000/api/todos/mark-completed)
    - param (optional): ids=[1,2,3]
- GET: [localhost:8000/api/todos/clear-completed](http://localhost:8000/api/todos/clear-completed)
    - param (optional): ids=[1,2,3]
- GET: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
- PUT: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
    - params (optional): text="Go to sleep", color=green|blue|orange|purple|red, completed=true|false
- DELETE: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)

## GET: [localhost:8000/api/todos](http://localhost:8000/api/todos)
### Route lấy danh sách các todo kèm với thông tin pagination

##### Param (không bắt buộc) - filter kết quả các todo:
- Param ```status``` với một trong các giá trị
    - ```all``` để lấy tất cả các todo chưa bị xóa
    - ```active``` để lấy tất cả các todo chưa hoàn thành  và chưa bị xóa
    - ```completed``` để lấy tất cả các todo đã hoàn thành  và chưa bị xóa
    - ```deleted``` để lấy tất cả các todo đã bị xóa
- Param ```colors``` với giá trị string như sau ```[green,blue,orange,purple,red]```
- Param ```sortBy``` với một trong các giá trị
    - ```dateDesc``` để sort theo ngày giảm dần (ngày mới nhất trước)
    - ```dateAsc``` để sort theo ngày tăng dần (ngày cũ nhất trước)
    - ```nameDesc``` để sort theo tên giảm dần (Z->A)
    - ```nameAsc``` để sort theo tên tăng dần (A->Z)

##### Khi không thêm param nào, mặc định route sẽ tìm tất cả các todo chưa bị xóa, kết quả được sort theo ngày mới nhất trước

##### Kết quả
Ví dụ kết quả trả về khi tìm thấy todo **(status code 200)**:

```
{
    "data": [
        {
            "id": 10,
            "text": "123",
            "completed": false,
            "color": null
        },
        {
            "id": 11,
            "text": "456",
            "completed": false,
            "color": null
        },
        {
            "id": 12,
            "text": "789",
            "completed": true,
            "color": {
                "name": "Green"
            }
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/todos?page=1",
        "last": "http://127.0.0.1:8000/api/todos?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/todos?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/todos",
        "per_page": 9,
        "to": 3,
        "total": 3
    }
}
```


Nếu không có todo nào thì kết quả như sau **(status code 404)**:

```
{
    "message": "Todos not found."
}
```

## POST: [localhost:8000/api/todos](http://localhost:8000/api/todos)
### Thêm một todo mới
##### Param (bắt buộc)
- ```text``` với giá trị là nội dung ngắn gọn của một todo, ví dụ ```Feed a cat```

##### Kết quả
Ví dụ kết quả trả về **(status code 200)**

```
{
    "data": {
        "id": 13,
        "text": "1010101",
        "completed": false,
        "color": null
    }
}
```


## GET: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
### Xem chi tiết một todo theo id
##### Kết quả
Ví dụ kết quả trả về với route: http://localhost:8000/api/todos/1

```
{
    "data": {
        "id": 1,
        "text": "Dicta atque optio praesentium esse",
        "completed": false,
        "color": {
            "name": "Purple"
        }
    }
}
```

Nếu không có todo nào thì kết quả như sau **(status code 404)**:

```
{
    "message": "Todo not found."
}
```

## PUT: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
### Update một todo theo id
##### Param (bắt buộc)
- Param ```text```
    - Giá trị là nội dung ngắn gọn của một todo, ví dụ ```Feed a cat```
- Param ```completed``` với một trong các giá trị sau:
    - ```true``` nghĩa là todo đã hoàn thành
    - ```false``` nghĩa là todo chưa hoàn thành
- Param ```color``` với một trong các giá trị string màu như ```green```, ```blue```, ```orange```, ```purple```, ```red```

##### Các yêu cầu với param
Nếu không có param nào được đưa vào, request sẽ bị từ chối **(status code 422)**

```
{
    "message": "The given data was invalid.",
    "errors": {
        "text": [
            "The text field is required when none of color / completed are present."
        ],
        "color": [
            "The color field is required when none of text / completed are present."
        ],
        "completed": [
            "The completed field is required when none of text / color are present."
        ]
    }
}
```

Nếu param ```color``` hoặc ```completed``` không hợp lệ, request sẽ bị từ chối **(status code 422)**

```
{
    "message": [
        "Color not found, please try again.",
        "Invalid status, please try again."
    ]
}
```

Ngoài ra, có thể update todo theo các thuộc tính riêng biệt, ví dụ
```PUT http://localhost:8000/api/todos/1``` với param ```text``` giá trị ```Do something else```
```PUT http://localhost:8000/api/todos/1``` với param ```color``` giá trị ```green```
```PUT http://localhost:8000/api/todos/1``` với param ```complete``` giá trị ```true```
```PUT http://localhost:8000/api/todos/1``` với param ```color``` giá trị ```green``` và ```complete``` giá trị ```true```

##### Kết quả
Kết quả trả về khi update thành công **(status code 200)**

```
{
    "data": {
        "id": 1,
        "text": "Dicta atque optio praesentium esse",
        "completed": true,
        "color": {
            "name": "Green"
        }
    }
}
```

Khi không tìm thấy todo để update sẽ báo lỗi **(status code 404)**

```
{
    "message": "Todo not found."
}
```

## DELETE: [localhost:8000/api/todos/{id}](http://localhost:8000/api/todos/1)
### Xóa một todo theo id
##### Kết quả
Kết quả trả về khi xóa thành công **(status code 200)**

```
{
    "data": {
        "id": 1,
        "text": "Dicta atque optio praesentium esse",
        "completed": true,
        "color": {
            "name": "Green"
        }
    }
}
```

Khi không tìm thấy todo để update sẽ báo lỗi **(status code 404)**

```
{
    "message": "Todo not found."
}
```

## GET: [localhost:8000/api/todos/mark-completed](http://localhost:8000/api/todos/mark-completed)
### Mark các todo được chọn hoặc tất cả thành trạng thái đã hoàn thành
##### Param (không bắt buộc)
- Param ```ids``` giá trị string ```[1,2,3]```

##### Kết quả
Nếu param ```ids``` được đưa vào sai định dạng, sẽ nhận thông báo sau **(status code 404)**

```
{
    "message": "No todos were marked as completed."
}
```

Nếu không có param được đưa vào, tất cả những todo sẽ được mark với thông báo sau  **(status code 200)**

```
{
    "message": "7 todo(s) were marked as completed."
}
```

## GET: [localhost:8000/api/todos/clear-completed](http://localhost:8000/api/todos/clear-completed)
### Mark các todo được chọn hoặc tất cả thành trạng thái đã hoàn thành
##### Param (không bắt buộc)
- Param ```ids``` giá trị string ```[1,2,3]```

##### Kết quả
Nếu param ```ids``` được đưa vào sai định dạng, sẽ nhận thông báo sau **(status code 404)**

```
{
    "message": "No todos were cleared."
}
```

Nếu không có param được đưa vào, tất cả những todo sẽ được mark với thông báo sau  **(status code 200)**

```
{
    "message": "9 todo(s) were cleared."
}
```

## Color route list
- GET: [localhost:8000/api/colors](http://localhost:8000/api/colors)
- POST: [localhost:8000/api/colors](http://localhost:8000/api/colors)
    - param: name="Black"
- GET: [localhost:8000/api/colors/{id}](http://localhost:8000/api/colors/1)
- PUT: [localhost:8000/api/colors/{id}](http://localhost:8000/api/colors/1)
    - param: name="Black"
- DELETE: [localhost:8000/api/colors/{id}](http://localhost:8000/api/colors/1)
