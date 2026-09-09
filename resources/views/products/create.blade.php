<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>

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
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            margin-bottom: 15px;
            color: #0d6efd;
            text-decoration: none;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Create Product</h2>

    <!-- Back to product list -->
    <a href="/products">← Back</a>

    <form id="form">
        <input id="product_name" placeholder="Name" required>
        <textarea id="detail" placeholder="Detail"></textarea>
        <input id="price" type="number" placeholder="Price" required>
        <button>Save Product</button>
    </form>
</div>

<script>

const API = "/api/products";

const form = document.getElementById('form');

form.onsubmit = e => {

    e.preventDefault();

    const button = form.querySelector('button');

    button.disabled = true;
    button.innerText = 'Saving...';


    fetch(API, {

        method: 'POST',

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

        location.href = "/products";

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

            alert('Unable to create product.');

        }

        button.disabled = false;
        button.innerText = 'Save Product';

    });

};

</script>

</body>
</html>
