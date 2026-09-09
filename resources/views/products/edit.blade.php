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


    fetch(`${API}/${ID}`, {

        method: 'PUT',

        headers: {

            'Content-Type': 'application/json',

            'Accept': 'application/json'

        },

        body: JSON.stringify({

            name:
                document.getElementById('product_name').value,

            detail:
                document.getElementById('detail').value,

            price:
                document.getElementById('price').value

        })

    })

    .then(async response => {

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        return data;

    })

    .then(response => {

        alert(response.message);

        location.href = '/products';

    })

    .catch(error => {

        console.error(error);

        if (error.errors) {

            const messages =
                Object.values(error.errors)
                    .flat()
                    .join('\n');

            alert(messages);

        } else {

            alert('Unable to update product.');

        }

        button.disabled = false;

        button.innerText = 'Update Product';

    });

};

</script>
</body>
</html>
