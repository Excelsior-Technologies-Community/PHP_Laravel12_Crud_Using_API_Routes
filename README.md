
# PHP_Laravel12_Crud_Using_API_Routes

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/PHP-8%2B-blue?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/API-REST-green?style=for-the-badge">
  <img src="https://img.shields.io/badge/Blade-Views-orange?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/MySQL-Database-lightgrey?style=for-the-badge&logo=mysql">
</p>

---

##  Overview

✔ Product management using REST APIs  
✔ Blade UI consuming APIs using Fetch API  
✔ Separate API & Web routes  
✔ Simple CRUD flow (Create, Read, Update, Delete)  

This project is built using **Laravel 12** and demonstrates how to use  
**API routes with Blade views** without any frontend framework.

---

##  Features

- Laravel 12 latest structure  
- REST API based Product CRUD  
- Blade views (main logic only, no CSS)  
- JavaScript Fetch API usage  
- MySQL database integration  
- Clean MVC architecture  
- Beginner friendly project  

---

##  Folder Structure

```text
product-crud/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProductController.php
│   │
│   └── Models/
│       └── Product.php
│
├── database/
│   └── migrations/
│       └── xxxx_create_products_table.php
│
├── resources/
│   └── views/
│       └── products/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── .env
├── artisan
├── composer.json
└── README.md
```

---

##  Step 1 — Install Laravel 12

```bash
composer create-project laravel/laravel product-crud "12.*"
```

Application URL:

```
http://127.0.0.1:8000
```

---

##  Step 2 — Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api
DB_USERNAME=root
DB_PASSWORD=
```

Create database manually:

```
api
```

---

##  Step 3 — Product Migration & Table

```bash
php artisan make:model Product -m
```

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('detail')->nullable();
        $table->decimal('price', 10, 2);
        $table->timestamps();
    });
}
```

```bash
php artisan migrate
```

---

##  Step 4 — Product Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'detail',
        'price'
    ];
}
```

---

##  Step 5 — Product Controller (API)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Product::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric'
        ]);

        $product = Product::create(
            $request->only('name', 'detail', 'price')
        );

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully'
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'status' => true,
            'data' => Product::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update(
            $request->only('name', 'detail', 'price')
        );

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully'
        ]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
```

---

##  Step 6 — API Routes

```php
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
```

---

##  Step 7 — Web Routes

```php
Route::view('/products', 'products.index');
Route::view('/products/create', 'products.create');
Route::view('/products/edit/{id}', 'products.edit');
```

---

##  Step 8 — Blade Files 

### resources/views/products/index.blade.php
```html
<!DOCTYPE html>
<html>
<head><title>Products</title></head>
<body>

<h2>Products</h2>
<a href="/products/create">Add Product</a>

<table border="1">
<thead>
<tr>
<th>ID</th><th>Name</th><th>Detail</th><th>Price</th><th>Action</th>
</tr>
</thead>
<tbody id="rows"></tbody>
</table>

<script>
fetch('/api/products')
.then(res=>res.json())
.then(res=>{
let html='';
res.data.forEach(p=>{
html+=`<tr>
<td>${p.id}</td>
<td>${p.name}</td>
<td>${p.detail ?? ''}</td>
<td>${p.price}</td>
<td>
<a href="/products/edit/${p.id}">Edit</a>
<button onclick="del(${p.id})">Delete</button>
</td>
</tr>`;
});
rows.innerHTML=html;
});

function del(id){
fetch('/api/products/'+id,{method:'DELETE'}).then(()=>location.reload());
}
</script>

</body>
</html>
```

### resources/views/products/create.blade.php
```html
<!DOCTYPE html>
<html>
<head><title>Create</title></head>
<body>

<form id="form">
<input id="name" placeholder="Name"><br>
<textarea id="detail"></textarea><br>
<input id="price" type="number"><br>
<button>Save</button>
</form>

<script>
form.onsubmit=e=>{
e.preventDefault();
fetch('/api/products',{
method:'POST',
headers:{'Content-Type':'application/json'},
body:JSON.stringify({
name:name.value,
detail:detail.value,
price:price.value
})
}).then(()=>location.href='/products');
}
</script>

</body>
</html>
```

### resources/views/products/edit.blade.php
```html
<!DOCTYPE html>
<html>
<head><title>Edit</title></head>
<body>

<form id="form">
<input id="name"><br>
<textarea id="detail"></textarea><br>
<input id="price" type="number"><br>
<button>Update</button>
</form>

<script>
const id=location.pathname.split('/').pop();

fetch('/api/products/'+id)
.then(res=>res.json())
.then(res=>{
name.value=res.data.name;
detail.value=res.data.detail;
price.value=res.data.price;
});

form.onsubmit=e=>{
e.preventDefault();
fetch('/api/products/'+id,{
method:'PUT',
headers:{'Content-Type':'application/json'},
body:JSON.stringify({
name:name.value,
detail:detail.value,
price:price.value
})
}).then(()=>location.href='/products');
}
</script>

</body>
</html>
```

---

##  Step 9 — Postman API Testing

Create Product  
POST  
http://127.0.0.1:8000/api/products

```json
{
  "name": "MOBILE",
  "detail": "64 GB",
  "price": 50000
}

```
<img width="809" height="882" alt="Screenshot 2025-12-22 123509" src="https://github.com/user-attachments/assets/bb8507e4-2ed8-4979-9acf-9f96d1efd8bf" />


Update Product  
PUT  
http://127.0.0.1:8000/api/products/6

```json
{
  "name": "MOBILE",
  "detail": "64 GB , Oneplus ",
  "price": 50000
}
```
<img width="773" height="857" alt="Screenshot 2025-12-22 123609" src="https://github.com/user-attachments/assets/3b3bd80e-8d6b-49f1-93a2-d702df6adfcf" />


Delete Product  
DELETE  
http://127.0.0.1:8000/api/products/6

<img width="752" height="729" alt="Screenshot 2025-12-22 123624" src="https://github.com/user-attachments/assets/0d3cf5d5-2390-4881-9f5f-551b482bee2a" />


List Product  
GET  
http://127.0.0.1:8000/api/products

<img width="805" height="942" alt="Screenshot 2025-12-22 123402" src="https://github.com/user-attachments/assets/1e974d68-efb8-41b1-8f68-c07d0ea80d6f" />


##   Frontend Side:-

Index Product(Blade View)

<img width="1188" height="494" alt="Screenshot 2025-12-22 123648" src="https://github.com/user-attachments/assets/2a790423-2382-4fd1-92fa-06642282b43d" />

Create Product(Blade View) 

<img width="671" height="512" alt="Screenshot 2025-12-22 130858" src="https://github.com/user-attachments/assets/2d455ab9-87b7-4e63-aed9-4a443242359a" />

Edit Product(Blade View) 

<img width="690" height="537" alt="Screenshot 2025-12-22 130908" src="https://github.com/user-attachments/assets/0169d5a1-1f8f-4eb0-a01c-f5cdda8360cc" />



