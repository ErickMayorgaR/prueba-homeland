<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
    <style>
        /* Estilos globales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #FF5722;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Grid para productos */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
        }

        /* Card del producto */
        .product-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            transition: transform 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .product-details {
            color: #333;
        }

        .product-name {
            font-weight: bold;
            font-size: 1.1em;
        }

        .product-price {
            margin: 10px 0;
            color: #FF5722;
            font-size: 1.2em;
        }

        .product-normal-price {
            text-decoration: line-through;
            color: #888;
            font-size: 0.9em;
        }

        /* Modal */
        #productModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #888;
        }

        .close-btn:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <h1>Catálogo de Productos</h1>
    <button id="addProductBtn">Agregar Producto</button>

    <!-- Contenedor de Productos -->
    <div class="product-grid" id="productGrid"></div>

    <!-- Modal para agregar productos -->
    <div id="productModal">
        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <form id="productForm">
                <label>Código:</label> <input type="text" id="product_code" required><br>
                <label>Nombre:</label> <input type="text" id="name" required><br>
                <label>Cantidad:</label> <input type="number" id="quantity" required><br>
                <label>Precio:</label> <input type="number" id="price" required><br>
                <label>Foto:</label> <input type="file" id="image" accept=".jpg, .png" required><br>
                <label>Fecha de Ingreso:</label> <input type="date" id="entry_date" required><br>
                <label>Fecha de Vencimiento:</label> <input type="date" id="expiry_date" required><br>
                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const modal = document.getElementById('productModal');
        const openModalBtn = document.getElementById('addProductBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const productGrid = document.getElementById('productGrid');

        // Abrir modal
        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        // Cerrar modal
        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Obtener productos
        async function fetchProducts() {
            const response = await fetch('/products');
            const data = await response.json();

            productGrid.innerHTML = ''; // Limpiar la cuadrícula
            data.forEach(product => {
                const productCard = `
                    <div class="product-card">
                        <img src="${product.image || 'https://via.placeholder.com/150'}" alt="${product.name}" class="product-image">
                        <div class="product-details">
                            <div class="product-name">${product.name}</div>
                            <div class="product-price">Q${product.benefit_price || product.price}</div>
                            <div class="product-normal-price">Q${product.price}</div>
                        </div>
                    </div>
                `;
                productGrid.innerHTML += productCard;
            });
        }

        // Guardar producto
        document.getElementById('productForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const response = await fetch('/products', {
                method: 'POST',
                body: formData,
            });
            if (response.ok) {
                fetchProducts();
                modal.style.display = 'none';
                e.target.reset();
            }
        });

        // Cargar productos al inicio
        fetchProducts();
    </script>
</body>
</html>
