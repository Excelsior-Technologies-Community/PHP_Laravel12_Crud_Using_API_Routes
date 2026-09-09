<!DOCTYPE html>
<html>

<head>
    <title>Product Management API</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 92%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .header {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin: 0 0 15px;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
        }

        a.btn {
            text-decoration: none;
            background: #0d6efd;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 5px;
        }

        /* Statistics */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            margin: 0 0 10px;
            font-size: 15px;
            color: #666;
        }

        .stat-card p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }

        /* Filters */

        .filters {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto auto;
            gap: 10px;
        }

        .filters input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-btn {
            background: #0d6efd;
            color: #fff;
        }

        .reset-btn {
            background: #6c757d;
            color: #fff;
        }

        /* Table */

        .table-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0d6efd;
            color: #ffffff;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .edit {
            color: #0d6efd;
            text-decoration: none;
            margin-right: 10px;
        }

        .delete {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Pagination */

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination button {
            border: 1px solid #ccc;
            background: #ffffff;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .pagination button.active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-info {
            margin-top: 10px;
            text-align: center;
            color: #666;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }

        @media (max-width: 800px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Header -->

    <div class="header">

        <h2>📦 Product Management</h2>

        <div class="top-bar">
            <a href="/products/create" class="btn">
                + Add Product
            </a>
        </div>

    </div>


    <!-- Statistics -->

    <div class="stats">

        <div class="stat-card">
            <h3>Total Products</h3>
            <p id="totalProducts">0</p>
        </div>

        <div class="stat-card">
            <h3>Average Price</h3>
            <p id="averagePrice">₹0</p>
        </div>

        <div class="stat-card">
            <h3>Highest Price</h3>
            <p id="highestPrice">₹0</p>
        </div>

        <div class="stat-card">
            <h3>Lowest Price</h3>
            <p id="lowestPrice">₹0</p>
        </div>

    </div>


    <!-- Search & Filters -->

    <div class="filters">

        <h3>🔎 Search & Filter Products</h3>

        <div class="filter-row">

            <input
                type="text"
                id="search"
                placeholder="Search by name or detail..."
            >

            <input
                type="number"
                id="minPrice"
                placeholder="Min Price"
                min="0"
            >

            <input
                type="number"
                id="maxPrice"
                placeholder="Max Price"
                min="0"
            >

            <button
                class="btn search-btn"
                onclick="searchProducts(1)"
            >
                Search
            </button>

            <button
                class="btn reset-btn"
                onclick="resetFilters()"
            >
                Reset
            </button>

        </div>

    </div>


    <!-- Products Table -->

    <div class="table-container">

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

            <tbody id="rows">

            <tr>
                <td colspan="5" class="loading">
                    Loading products...
                </td>
            </tr>

            </tbody>

        </table>


        <!-- Pagination -->

        <div
            id="pagination"
            class="pagination">
        </div>

        <div
            id="pageInfo"
            class="page-info">
        </div>

    </div>

</div>


<script>

const API = "/api/products";


// ==========================================
// Load Products
// ==========================================

function loadProducts(page = 1) {

    const search = document
        .getElementById('search')
        .value
        .trim();

    const minPrice = document
        .getElementById('minPrice')
        .value;

    const maxPrice = document
        .getElementById('maxPrice')
        .value;


    let params = new URLSearchParams();

    params.append('page', page);


    if (search !== '') {
        params.append('search', search);
    }

    if (minPrice !== '') {
        params.append('min_price', minPrice);
    }

    if (maxPrice !== '') {
        params.append('max_price', maxPrice);
    }


    document.getElementById('rows').innerHTML = `
        <tr>
            <td colspan="5" class="loading">
                Loading products...
            </td>
        </tr>
    `;


    fetch(`${API}?${params.toString()}`, {
        headers: {
            'Accept': 'application/json'
        }
    })

    .then(response => {

        if (!response.ok) {
            throw new Error('Failed to load products');
        }

        return response.json();

    })

    .then(response => {

        displayProducts(response.data);

        displayPagination(response.pagination);

    })

    .catch(error => {

        console.error(error);

        document.getElementById('rows').innerHTML = `
            <tr>
                <td colspan="5" class="empty">
                    Failed to load products.
                </td>
            </tr>
        `;

    });
}


// ==========================================
// Display Products
// ==========================================

function displayProducts(products) {

    let html = '';


    if (products.length === 0) {

        html = `
            <tr>
                <td colspan="5" class="empty">
                    No products found.
                </td>
            </tr>
        `;

    } else {

        products.forEach(product => {

            html += `
                <tr>

                    <td>${product.id}</td>

                    <td>${escapeHtml(product.name)}</td>

                    <td>
                        ${escapeHtml(product.detail ?? '')}
                    </td>

                    <td>
                        ₹${Number(product.price).toLocaleString('en-IN')}
                    </td>

                    <td>

                        <a
                            href="/products/edit/${product.id}"
                            class="edit">
                            Edit
                        </a>

                        <button
                            class="delete"
                            onclick="deleteProduct(${product.id})">
                            Delete
                        </button>

                    </td>

                </tr>
            `;

        });

    }


    document.getElementById('rows').innerHTML = html;
}


// ==========================================
// Pagination
// ==========================================

function displayPagination(pagination) {

    const container =
        document.getElementById('pagination');

    container.innerHTML = '';


    if (pagination.last_page <= 1) {

        document.getElementById('pageInfo').innerHTML =
            `Showing ${pagination.total} product(s)`;

        return;
    }


    // Previous button

    const previous = document.createElement('button');

    previous.innerText = '← Previous';

    previous.disabled =
        pagination.current_page === 1;

    previous.onclick = function () {

        loadProducts(
            pagination.current_page - 1
        );

    };

    container.appendChild(previous);


    // Page numbers

    for (
        let page = 1;
        page <= pagination.last_page;
        page++
    ) {

        const button =
            document.createElement('button');

        button.innerText = page;


        if (
            page === pagination.current_page
        ) {

            button.classList.add('active');

        }


        button.onclick = function () {

            loadProducts(page);

        };


        container.appendChild(button);

    }


    // Next button

    const next = document.createElement('button');

    next.innerText = 'Next →';

    next.disabled =
        pagination.current_page ===
        pagination.last_page;

    next.onclick = function () {

        loadProducts(
            pagination.current_page + 1
        );

    };

    container.appendChild(next);


    // Page information

    document.getElementById('pageInfo').innerHTML =
        `Showing ${pagination.from ?? 0}
        to ${pagination.to ?? 0}
        of ${pagination.total} products`;
}


// ==========================================
// Search
// ==========================================

function searchProducts(page = 1) {

    loadProducts(page);

}


// ==========================================
// Reset Filters
// ==========================================

function resetFilters() {

    document.getElementById('search').value = '';

    document.getElementById('minPrice').value = '';

    document.getElementById('maxPrice').value = '';

    loadProducts(1);

}


// ==========================================
// Delete Product
// ==========================================

function deleteProduct(id) {

    if (!confirm('Are you sure you want to delete this product?')) {
        return;
    }


    fetch(`${API}/${id}`, {

        method: 'DELETE',

        headers: {
            'Accept': 'application/json'
        }

    })

    .then(response => {

        if (!response.ok) {
            throw new Error('Delete failed');
        }

        return response.json();

    })

    .then(response => {

        alert(response.message);

        loadProducts(1);

        loadStatistics();

    })

    .catch(error => {

        console.error(error);

        alert('Unable to delete product.');

    });

}


// ==========================================
// Load Statistics
// ==========================================

function loadStatistics() {

    fetch(`${API}/statistics`, {

        headers: {
            'Accept': 'application/json'
        }

    })

    .then(response => {

        if (!response.ok) {
            throw new Error('Statistics request failed');
        }

        return response.json();

    })

    .then(response => {

        const data = response.data;


        document.getElementById(
            'totalProducts'
        ).innerText =
            data.total_products;


        document.getElementById(
            'averagePrice'
        ).innerText =
            formatCurrency(data.average_price);


        document.getElementById(
            'highestPrice'
        ).innerText =
            formatCurrency(data.highest_price);


        document.getElementById(
            'lowestPrice'
        ).innerText =
            formatCurrency(data.lowest_price);

    })

    .catch(error => {

        console.error(error);

    });

}


// ==========================================
// Currency Formatting
// ==========================================

function formatCurrency(value) {

    return '₹' +
        Number(value).toLocaleString('en-IN', {
            maximumFractionDigits: 2
        });

}


// ==========================================
// Prevent HTML Injection
// ==========================================

function escapeHtml(value) {

    const div =
        document.createElement('div');

    div.textContent =
        value ?? '';

    return div.innerHTML;

}


// ==========================================
// Initial Load
// ==========================================

loadProducts(1);

loadStatistics();

</script>

</body>

</html>