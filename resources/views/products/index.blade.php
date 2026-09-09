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
            width: 94%;
            max-width: 1200px;
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
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .top-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        a.btn,
        button.btn {
            text-decoration: none;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
        }

        .primary {
            background: #0d6efd;
        }

        .success {
            background: #198754;
        }

        .warning {
            background: #ffc107;
            color: #000 !important;
        }

        .danger {
            background: #dc3545;
        }

        .secondary {
            background: #6c757d;
        }

        /* Statistics */

        .stats {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            padding: 18px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            margin: 0 0 10px;
            font-size: 14px;
            color: #666;
        }

        .stat-card p {
            margin: 0;
            font-size: 21px;
            font-weight: bold;
            color: #0d6efd;
        }

        /* Filters */

        .filters {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .filter-row {
            display: grid;
            grid-template-columns:
                2fr 1fr 1fr 1fr 1fr auto auto;
            gap: 10px;
        }

        .filters input,
        .filters select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Table */

        .table-container {
            background: #fff;
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
            color: #fff;
            white-space: nowrap;
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

        .sortable {
            cursor: pointer;
        }

        .sortable:hover {
            background: #084298;
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

        .status-btn {
            border: none;
            padding: 6px 10px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
        }

        .active-status {
            background: #d1e7dd;
            color: #0f5132;
        }

        .inactive-status {
            background: #f8d7da;
            color: #842029;
        }

        /* Bulk */

        .bulk-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            background: #fff3cd;
            border: 1px solid #ffe69c;
            border-radius: 6px;
        }

        .bulk-bar button {
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            background: #dc3545;
            color: #fff;
        }

        /* Pagination */

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination button {
            border: 1px solid #ccc;
            background: #fff;
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

        .loading,
        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        @media (max-width: 1100px) {

            .stats {
                grid-template-columns: repeat(3, 1fr);
            }

            .filter-row {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .top-bar {
                align-items: stretch;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <!-- HEADER -->

        <div class="header">

            <h2>📦 Product Management</h2>

            <div class="top-bar">

                <strong>
                    Laravel Product API
                </strong>

                <div class="top-actions">

                    <a
                        href="/products/create"
                        class="btn primary">
                        + Add Product
                    </a>

                    <button
                        class="btn success"
                        onclick="exportCsv()">
                        📥 Export CSV
                    </button>

                    <button
                        class="btn warning"
                        onclick="loadTrash()">
                        🗑️ Trash
                    </button>

                </div>

            </div>

        </div>


        <!-- STATISTICS -->

        <div class="stats">

            <div class="stat-card">
                <h3>Total Products</h3>
                <p id="totalProducts">0</p>
            </div>

            <div class="stat-card">
                <h3>Active</h3>
                <p id="activeProducts">0</p>
            </div>

            <div class="stat-card">
                <h3>Inactive</h3>
                <p id="inactiveProducts">0</p>
            </div>

            <div class="stat-card">
                <h3>Trash</h3>
                <p id="trashedProducts">0</p>
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


        <!-- FILTERS -->

        <div class="filters">

            <h3>🔎 Search & Filters</h3>

            <div class="filter-row">

                <input
                    type="text"
                    id="search"
                    placeholder="Search name or detail...">

                <input
                    type="number"
                    id="minPrice"
                    placeholder="Min Price"
                    min="0">

                <input
                    type="number"
                    id="maxPrice"
                    placeholder="Max Price"
                    min="0">

                <select id="status">

                    <option value="">
                        All Status
                    </option>

                    <option value="active">
                        Active
                    </option>

                    <option value="inactive">
                        Inactive
                    </option>

                </select>


                <select id="perPage">

                    <option value="5">
                        5 / Page
                    </option>

                    <option value="10">
                        10 / Page
                    </option>

                    <option value="20">
                        20 / Page
                    </option>

                    <option value="50">
                        50 / Page
                    </option>

                </select>


                <button
                    class="btn primary"
                    onclick="searchProducts(1)">
                    Search
                </button>


                <button
                    class="btn secondary"
                    onclick="resetFilters()">
                    Reset
                </button>

            </div>

        </div>


        <!-- BULK ACTION -->

        <div
            class="bulk-bar"
            id="bulkBar"
            style="display:none;">

            <span>
                <strong id="selectedCount">0</strong>
                product(s) selected
            </span>

            <button onclick="bulkDelete()">
                🗑️ Delete Selected
            </button>

        </div>


        <!-- TABLE -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>
                            <input
                                type="checkbox"
                                id="selectAll"
                                class="checkbox"
                                onchange="toggleSelectAll()">
                        </th>

                        <th
                            class="sortable"
                            onclick="sortProducts('id')">
                            ID ↕
                        </th>

                        <th
                            class="sortable"
                            onclick="sortProducts('name')">
                            Name ↕
                        </th>

                        <th>
                            Detail
                        </th>

                        <th
                            class="sortable"
                            onclick="sortProducts('price')">
                            Price ↕
                        </th>

                        <th>
                            Status
                        </th>

                        <th
                            class="sortable"
                            onclick="sortProducts('created_at')">
                            Created ↕
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody id="rows">

                    <tr>

                        <td
                            colspan="8"
                            class="loading">
                            Loading products...
                        </td>

                    </tr>

                </tbody>

            </table>


            <div
                id="pagination"
                class="pagination"></div>


            <div
                id="pageInfo"
                class="page-info"></div>

        </div>

    </div>


    <script>
        const API = "/api/products";

        let currentPage = 1;

        let currentSortBy = "id";

        let currentSortOrder = "asc";


        /*
        |--------------------------------------------------------------------------
        | Load Products
        |--------------------------------------------------------------------------
        */

        function loadProducts(page = 1) {
            currentPage = page;

            const search =
                document
                .getElementById("search")
                .value
                .trim();

            const minPrice =
                document
                .getElementById("minPrice")
                .value;

            const maxPrice =
                document
                .getElementById("maxPrice")
                .value;

            const status =
                document
                .getElementById("status")
                .value;

            const perPage =
                document
                .getElementById("perPage")
                .value;


            const params =
                new URLSearchParams();


            params.append(
                "page",
                page
            );


            params.append(
                "per_page",
                perPage
            );


            params.append(
                "sort_by",
                currentSortBy
            );


            params.append(
                "sort_order",
                currentSortOrder
            );


            if (search !== "") {

                params.append(
                    "search",
                    search
                );

            }


            if (minPrice !== "") {

                params.append(
                    "min_price",
                    minPrice
                );

            }


            if (maxPrice !== "") {

                params.append(
                    "max_price",
                    maxPrice
                );

            }


            if (status !== "") {

                params.append(
                    "status",
                    status
                );

            }


            document.getElementById("rows").innerHTML = `
        <tr>
            <td colspan="8" class="loading">
                Loading products...
            </td>
        </tr>
    `;


            fetch(`${API}?${params.toString()}`, {

                    headers: {
                        "Accept": "application/json"
                    }

                })

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            "Failed to load products"
                        );

                    }

                    return response.json();

                })

                .then(response => {

                    displayProducts(
                        response.data
                    );

                    displayPagination(
                        response.pagination
                    );

                    updateBulkBar();

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById(
                        "rows"
                    ).innerHTML = `
            <tr>
                <td
                    colspan="8"
                    class="empty"
                >
                    Failed to load products.
                </td>
            </tr>
        `;

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Display Products
        |--------------------------------------------------------------------------
        */

        function displayProducts(products) {
            let html = "";


            if (products.length === 0) {

                html = `
            <tr>
                <td
                    colspan="8"
                    class="empty"
                >
                    No products found.
                </td>
            </tr>
        `;

            } else {

                products.forEach(product => {

                    const statusClass =
                        product.status === "active" ?
                        "active-status" :
                        "inactive-status";


                    const statusText =
                        product.status === "active" ?
                        "Active" :
                        "Inactive";


                    html += `

                <tr>

                    <td>

                        <input
                            type="checkbox"
                            class="product-checkbox checkbox"
                            value="${product.id}"
                            onchange="updateBulkBar()"
                        >

                    </td>


                    <td>
                        ${product.id}
                    </td>


                    <td>
                        ${escapeHtml(product.name)}
                    </td>


                    <td>
                        ${escapeHtml(product.detail ?? "")}
                    </td>


                    <td>
                        ₹${Number(product.price)
                            .toLocaleString("en-IN")}
                    </td>


                    <td>

                        <button
                            class="status-btn ${statusClass}"
                            onclick="toggleStatus(${product.id})"
                        >
                            ${statusText}
                        </button>

                    </td>


                    <td>
                        ${formatDate(product.created_at)}
                    </td>


                    <td>

                        <a
                            href="/products/edit/${product.id}"
                            class="edit"
                        >
                            Edit
                        </a>


                        <button
                            class="delete"
                            onclick="deleteProduct(${product.id})"
                        >
                            Delete
                        </button>

                    </td>

                </tr>

            `;

                });

            }


            document.getElementById(
                "rows"
            ).innerHTML = html;


            document.getElementById(
                "selectAll"
            ).checked = false;

        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        function sortProducts(column) {
            if (currentSortBy === column) {

                currentSortOrder =
                    currentSortOrder === "asc" ?
                    "desc" :
                    "asc";

            } else {

                currentSortBy = column;

                currentSortOrder = "asc";

            }


            loadProducts(1);
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        function displayPagination(pagination) {
            const container =
                document.getElementById(
                    "pagination"
                );


            container.innerHTML = "";


            if (pagination.last_page <= 1) {

                document.getElementById(
                        "pageInfo"
                    ).innerHTML =
                    `Showing ${pagination.total} product(s)`;

                return;

            }


            const previous =
                document.createElement("button");


            previous.innerText =
                "← Previous";


            previous.disabled =
                pagination.current_page === 1;


            previous.onclick = function() {

                loadProducts(
                    pagination.current_page - 1
                );

            };


            container.appendChild(
                previous
            );


            for (
                let page = 1; page <= pagination.last_page; page++
            ) {

                const button =
                    document.createElement("button");


                button.innerText =
                    page;


                if (
                    page ===
                    pagination.current_page
                ) {

                    button.classList.add(
                        "active"
                    );

                }


                button.onclick = function() {

                    loadProducts(page);

                };


                container.appendChild(
                    button
                );

            }


            const next =
                document.createElement("button");


            next.innerText =
                "Next →";


            next.disabled =
                pagination.current_page ===
                pagination.last_page;


            next.onclick = function() {

                loadProducts(
                    pagination.current_page + 1
                );

            };


            container.appendChild(
                next
            );


            document.getElementById(
                    "pageInfo"
                ).innerHTML =
                `Showing ${pagination.from ?? 0}
        to ${pagination.to ?? 0}
        of ${pagination.total} products`;
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        function searchProducts(page = 1) {
            loadProducts(page);
        }


        /*
        |--------------------------------------------------------------------------
        | Reset Filters
        |--------------------------------------------------------------------------
        */

        function resetFilters() {
            document.getElementById(
                "search"
            ).value = "";


            document.getElementById(
                "minPrice"
            ).value = "";


            document.getElementById(
                "maxPrice"
            ).value = "";


            document.getElementById(
                "status"
            ).value = "";


            document.getElementById(
                "perPage"
            ).value = "5";


            currentSortBy = "id";

            currentSortOrder = "asc";


            loadProducts(1);
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Product
        |--------------------------------------------------------------------------
        */

        function deleteProduct(id) {
            if (
                !confirm(
                    "Move this product to trash?"
                )
            ) {

                return;

            }


            fetch(`${API}/${id}`, {

                    method: "DELETE",

                    headers: {
                        "Accept": "application/json"
                    }

                })

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            "Delete failed"
                        );

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

                    alert(
                        "Unable to delete product."
                    );

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Toggle Status
        |--------------------------------------------------------------------------
        */

        function toggleStatus(id) {
            fetch(
                    `${API}/${id}/toggle-status`, {
                        method: "PATCH",

                        headers: {
                            "Accept": "application/json"
                        }
                    }
                )

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            "Status update failed"
                        );

                    }

                    return response.json();

                })

                .then(response => {

                    loadProducts(
                        currentPage
                    );

                    loadStatistics();

                })

                .catch(error => {

                    console.error(error);

                    alert(
                        "Unable to update status."
                    );

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Select All
        |--------------------------------------------------------------------------
        */

        function toggleSelectAll() {
            const checked =
                document.getElementById(
                    "selectAll"
                ).checked;


            document
                .querySelectorAll(
                    ".product-checkbox"
                )
                .forEach(checkbox => {

                    checkbox.checked =
                        checked;

                });


            updateBulkBar();
        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Selection Counter
        |--------------------------------------------------------------------------
        */

        function updateBulkBar() {
            const selected =
                document.querySelectorAll(
                    ".product-checkbox:checked"
                );


            const bar =
                document.getElementById(
                    "bulkBar"
                );


            const count =
                document.getElementById(
                    "selectedCount"
                );


            count.innerText =
                selected.length;


            bar.style.display =
                selected.length > 0 ?
                "flex" :
                "none";
        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Delete
        |--------------------------------------------------------------------------
        */

        function bulkDelete() {
            const selected =
                Array.from(
                    document.querySelectorAll(
                        ".product-checkbox:checked"
                    )
                ).map(
                    checkbox =>
                    Number(checkbox.value)
                );


            if (selected.length === 0) {

                alert(
                    "Please select at least one product."
                );

                return;

            }


            if (
                !confirm(
                    `Move ${selected.length} product(s) to trash?`
                )
            ) {

                return;

            }


            fetch(
                    `${API}/bulk-delete`, {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",

                            "Accept": "application/json"
                        },

                        body: JSON.stringify({
                            ids: selected
                        })

                    }
                )

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            "Bulk delete failed"
                        );

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

                    alert(
                        "Unable to delete selected products."
                    );

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Export CSV
        |--------------------------------------------------------------------------
        */

        function exportCsv() {
            const search =
                document.getElementById(
                    "search"
                ).value.trim();


            const minPrice =
                document.getElementById(
                    "minPrice"
                ).value;


            const maxPrice =
                document.getElementById(
                    "maxPrice"
                ).value;


            const status =
                document.getElementById(
                    "status"
                ).value;


            const params =
                new URLSearchParams();


            if (search !== "") {

                params.append(
                    "search",
                    search
                );

            }


            if (minPrice !== "") {

                params.append(
                    "min_price",
                    minPrice
                );

            }


            if (maxPrice !== "") {

                params.append(
                    "max_price",
                    maxPrice
                );

            }


            if (status !== "") {

                params.append(
                    "status",
                    status
                );

            }


            params.append(
                "sort_by",
                currentSortBy
            );


            params.append(
                "sort_order",
                currentSortOrder
            );


            window.location.href =
                `/api/products/export?${params.toString()}`;
        }


        /*
        |--------------------------------------------------------------------------
        | Load Trash
        |--------------------------------------------------------------------------
        */

        function loadTrash() {
            fetch(
                    `${API}/trash`, {
                        headers: {
                            "Accept": "application/json"
                        }
                    }
                )

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            "Trash request failed"
                        );

                    }

                    return response.json();

                })

                .then(response => {

                    if (
                        response.data.length === 0
                    ) {

                        alert(
                            "Trash is empty."
                        );

                        return;

                    }


                    let message =
                        "TRASH PRODUCTS\n\n";


                    response.data.forEach(
                        product => {

                            message +=
                                `ID: ${product.id} | ` +
                                `${product.name}\n`;

                        }
                    );


                    message +=
                        "\nRestore a product using its ID from the API.";

                    alert(message);

                })

                .catch(error => {

                    console.error(error);

                    alert(
                        "Unable to load trash."
                    );

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Load Statistics
        |--------------------------------------------------------------------------
        */

        function loadStatistics() {
            fetch(
                    `${API}/statistics`, {
                        headers: {
                            "Accept": "application/json"
                        }
                    }
                )

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            "Statistics request failed"
                        );

                    }

                    return response.json();

                })

                .then(response => {

                    const data =
                        response.data;


                    document.getElementById(
                            "totalProducts"
                        ).innerText =
                        data.total_products;


                    document.getElementById(
                            "activeProducts"
                        ).innerText =
                        data.active_products;


                    document.getElementById(
                            "inactiveProducts"
                        ).innerText =
                        data.inactive_products;


                    document.getElementById(
                            "trashedProducts"
                        ).innerText =
                        data.trashed_products;


                    document.getElementById(
                            "highestPrice"
                        ).innerText =
                        formatCurrency(
                            data.highest_price
                        );


                    document.getElementById(
                            "lowestPrice"
                        ).innerText =
                        formatCurrency(
                            data.lowest_price
                        );

                })

                .catch(error => {

                    console.error(error);

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Currency
        |--------------------------------------------------------------------------
        */

        function formatCurrency(value) {
            return "₹" +
                Number(value).toLocaleString(
                    "en-IN", {
                        maximumFractionDigits: 2
                    }
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        function formatDate(value) {
            if (!value) {

                return "-";

            }


            const date =
                new Date(value);


            return date.toLocaleDateString(
                "en-IN"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Escape HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {
            const div =
                document.createElement(
                    "div"
                );


            div.textContent =
                value ?? "";


            return div.innerHTML;
        }


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        loadProducts(1);

        loadStatistics();
    </script>

</body>

</html>