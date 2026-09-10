<!DOCTYPE html>
<html>

<head>

    <title>Create Product</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }

        .container {
            width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

        input,
        textarea,
        select {
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
            cursor: pointer;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Create Product</h2>

        <a href="/products">
            ← Back
        </a>

        <form id="form">

            <input
                id="product_name"
                placeholder="Name"
                required>


            <textarea
                id="detail"
                placeholder="Detail"></textarea>


            <input
                id="price"
                type="number"
                placeholder="Price"
                min="0"
                step="0.01"
                required>

            <label for="image">Product image</label>
            <input id="image" type="file" accept="image/jpeg,image/png,image/webp">


            <select id="status">

                <option value="active">
                    Active
                </option>

                <option value="inactive">
                    Inactive
                </option>

            </select>


            <button type="submit">
                Save Product
            </button>

        </form>

    </div>


    <script>
        const API = "/api/products";

        const form =
            document.getElementById("form");


        form.onsubmit = function(e) {
            e.preventDefault();


            const button =
                form.querySelector("button");


            button.disabled = true;

            button.innerText =
                "Saving...";


                const formData = new FormData();
                formData.append("name", document.getElementById("product_name").value);
                formData.append("detail", document.getElementById("detail").value);
                formData.append("price", document.getElementById("price").value);
                formData.append("status", document.getElementById("status").value);

                const image = document.getElementById("image").files[0];
                if (image) formData.append("image", image);

                fetch(API, {

                    method: "POST",

                    headers: {
                        "Accept": "application/json"

                    },

                    body: formData

                })

                .then(async response => {

                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw data;

                    }


                    return data;

                })

                .then(response => {

                    Swal.fire({
                        icon: "success",
                        title: "Product created",
                        text: response.message,
                        timer: 1400,
                        showConfirmButton: false
                    }).then(() => location.href = "/products");

                })

                .catch(error => {

                    console.error(error);


                    if (error.errors) {

                        const messages =
                            Object.values(
                                error.errors
                            )
                            .flat()
                            .join("\n");


                        Swal.fire("Validation error", messages, "error");

                    } else {

                        Swal.fire("Error", "Unable to create product.", "error");

                    }


                    button.disabled = false;

                    button.innerText =
                        "Save Product";

                });

        };
    </script>

</body>

</html>