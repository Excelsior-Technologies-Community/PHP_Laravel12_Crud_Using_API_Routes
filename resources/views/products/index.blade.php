<!DOCTYPE html>
<html>
<head>
    <title>Products</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 15px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        a.btn {
            text-decoration: none;
            background: #0d6efd;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #0d6efd;
            color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        button {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        a.edit {
            margin-right: 8px;
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Products</h2>

    <!-- Add product button -->
    <div class="top-bar">
        <span></span>
        <a href="/products/create" class="btn">+ Add Product</a>
    </div>

    <!-- Products table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Detail</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="rows"></tbody>
    </table>
</div>

<script>
const API = "/api/products"; // API base URL

// Fetch all products
fetch(API)
.then(res => res.json())
.then(res => {
    let html = '';
    res.data.forEach(p => {
        html += `
        <tr>
            <td>${p.id}</td>
            <td>${p.name}</td>
            <td>${p.detail ?? ''}</td>
            <td>${p.price}</td>
            <td>
                <a href="/products/edit/${p.id}" class="edit">Edit</a>
                <button onclick="del(${p.id})">Delete</button>
            </td>
        </tr>`;
    });
    document.getElementById('rows').innerHTML = html;
});

// Delete product
function del(id) {
    if (!confirm('Delete product?')) return;

    fetch(`${API}/${id}`, { method: 'DELETE' })
        .then(() => location.reload());
}
</script>

</body>
</html>
