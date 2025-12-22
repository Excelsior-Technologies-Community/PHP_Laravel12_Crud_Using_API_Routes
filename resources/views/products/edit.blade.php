<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .container {
            width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            background: #0d6efd;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
        }
        a {
            text-decoration: none;
            color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>

    <!-- Back to product list -->
    <a href="/products">← Back</a>

    <form id="form">
        <label>Name</label>
        <input id="product_name">

        <label>Detail</label>
        <textarea id="detail"></textarea>

        <label>Price</label>
        <input id="price" type="number">

        <button>Update Product</button>
    </form>
</div>

<script>
const API = "/api/products"; // API base URL
const ID = location.pathname.split('/').pop(); // Get product ID

// Load product data
fetch(`${API}/${ID}`)
.then(res => res.json())
.then(res => {
    document.getElementById('product_name').value = res.data.name;
    document.getElementById('detail').value = res.data.detail ?? '';
    document.getElementById('price').value = res.data.price;
});

// Submit update form
form.onsubmit = e => {
    e.preventDefault();

    fetch(`${API}/${ID}`, {
        method: 'PUT',
        headers: {
            'Content-Type':'application/json',
            'Accept':'application/json'
        },
        body: JSON.stringify({
            name: document.getElementById('product_name').value,
            detail: detail.value,
            price: price.value
        })
    }).then(() => location.href = "/products"); // Redirect after update
};
</script>

</body>
</html>
