<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        <label>Product image</label>
        <input id="image" type="file" accept="image/jpeg,image/png,image/webp">
        <img id="current_image" alt="Current product image" style="display:none;max-width:120px;margin-bottom:12px">

        <button>Update Product</button>
    </form>
</div>

<script>

const API = "/api/products";

const ID = location.pathname.split('/').pop();

const form = document.getElementById('form');


// ==========================================
// Load Product
// ==========================================

fetch(`${API}/${ID}`, {

    headers: {
        'Accept': 'application/json'
    }

})

.then(response => {

    if (!response.ok) {
        throw new Error('Product not found');
    }

    return response.json();

})

.then(response => {

    document.getElementById('product_name').value =
        response.data.name;

    document.getElementById('detail').value =
        response.data.detail ?? '';

    document.getElementById('price').value =
        response.data.price;

    if (response.data.image_path) {
        const image = document.getElementById('current_image');
        image.src = `/storage/${response.data.image_path}`;
        image.style.display = 'block';
    }

})

.catch(error => {

    console.error(error);

    alert('Unable to load product.');

    location.href = '/products';

});


// ==========================================
// Update Product
// ==========================================

form.onsubmit = e => {

    e.preventDefault();

    const button =
        form.querySelector('button');

    button.disabled = true;

    button.innerText = 'Updating...';


    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', document.getElementById('product_name').value);
    formData.append('detail', document.getElementById('detail').value);
    formData.append('price', document.getElementById('price').value);
    const image = document.getElementById('image').files[0];
    if (image) formData.append('image', image);

    fetch(`${API}/${ID}`, {

        method: 'POST',

        headers: {

            'Accept': 'application/json'

        },

        body: formData

    })

    .then(async response => {

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        return data;

    })

    .then(response => {

        Swal.fire({
            icon: 'success',
            title: 'Product updated',
            text: response.message,
            timer: 1400,
            showConfirmButton: false
        }).then(() => location.href = '/products');

    })

    .catch(error => {

        console.error(error);

        if (error.errors) {

            const messages =
                Object.values(error.errors)
                    .flat()
                    .join('\n');

            Swal.fire('Validation error', messages, 'error');

        } else {

            Swal.fire('Error', 'Unable to update product.', 'error');

        }

        button.disabled = false;

        button.innerText = 'Update Product';

    });

};

</script>
</body>
</html>
