<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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


        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
        }

        .product-card {
            background: #fff;
            max-width: 300px;
            margin: 0 auto;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }


        .product-card:hover {
            transform: translateY(-5px);
        }

        /* Estilo para la imagen del producto */
        .product-image {
            max-width: 100%;
            /* Ajuste estándar */
            height: auto;
            margin: 0 auto;
            display: block;
        }

        /* Reducción específica si solo hay un producto */
        .product-grid:only-child .product-card .product-image {
            max-width: 25%;
            /* Limita al 25% de la pantalla */
        }

        .actions {
            margin-top: 10px;
        }

        .edit-btn,
        .delete-btn {
            margin: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        /* Modal */
        #productModal,
        #confirmModal {
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
        }

        .error {
            color: red;
            font-size: 0.8em;
            display: none;
        }

        .close-btn {
            cursor: pointer;
            color: red;
            font-weight: bold;
            font-size: 1.5em;
            position: absolute;
            top: 10px;
            right: 15px;
        }

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
            z-index: 1000;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            text-align: left;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease;
        }

        .modal-content label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #333;
        }

        .modal-content input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-content input:focus {
            outline: none;
            border-color: #FF5722;
            box-shadow: 0 0 5px rgba(255, 87, 34, 0.5);
        }

        .modal-content button {
            background-color: #FF5722;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-size: 1em;
            margin-top: 10px;
        }

        .modal-content button:hover {
            background-color: #e64a19;
        }


        .close-btn:hover {
            color: #000;
        }

        /* Animación de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Error en inputs */
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: -10px;
            margin-bottom: 10px;
            display: none;
        }

        #pagination {
            display: flex;
            justify-content: center;
            /* Centra horizontalmente */
            align-items: center;
            /* Alinea verticalmente */
            gap: 10px;
            /* Espacio entre botones */
            margin-top: 20px;
            margin-bottom: 20px;
        }

        #pagination button {
            background-color: #fff;
            border: 1px solid #FF5722;
            color: #FF5722;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
            margin: 0;
            /* Elimina cualquier margen */
        }

        #pagination button:hover {
            background-color: #FF5722;
            color: #fff;
        }

        #pagination button.active {
            background-color: #FF5722;
            color: #fff;
            border: 1px solid #FF5722;
        }
    </style>
</head>

<body>
    <h1>Catálogo de Productos</h1>
    <button id="addProductBtn">Agregar Producto</button>

    <!-- Contenedor de Productos -->
    <div class="product-grid" id="productGrid"></div>
    <div id="pagination" style="text-align: center; margin-top: 20px;"></div>

    <!-- Modal para agregar y editar productos -->
    <div id="productModal">

        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <form id="productForm">
                <label>Código:</label>
                <input type="text" id="product_code" name="product_code" required>
                <span class="error" id="codeError">El código es obligatorio y solo puede contener letras y números.</span>

                <label>Nombre:</label>
                <input type="text" id="name" name="name" required>
                <span class="error" id="nameError">El nombre solo puede contener letras y espacios.</span>

                <label>Cantidad:</label>
                <input type="number" id="quantity" name="quantity" required>
                <span class="error" id="quantityError">La cantidad debe ser mayor a 0.</span>

                <label>Precio:</label>
                <input type="number" step="0.01" id="price" name="price" required>
                <span class="error" id="priceError">El precio debe ser mayor a 0.</span>

                <label>Foto:</label>
                <input type="file" id="image" name="image" accept=".jpg, .png" required>
                <span class="error" id="imageError">Solo se permiten archivos .jpg o .png.</span>

                <label>Fecha de Ingreso:</label>
                <input type="text" id="entry_date" name="entry_date" placeholder="dd/mm/yyyy" required>
                <span class="error" id="entryDateError">La fecha de ingreso es obligatoria.</span>

                <label>Fecha de Vencimiento:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="dd/mm/yyyy" required>
                <span class="error" id="expiryDateError">La fecha de vencimiento debe ser mayor a la fecha de ingreso.</span>

                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmModal">
        <div class="modal-content">
            <p>¿Seguro que deseas eliminar este producto?</p>
            <button id="confirmDeleteBtn">Eliminar</button>
            <button id="cancelDeleteBtn">Cancelar</button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const productGrid = document.getElementById('productGrid');

        const addProductBtn = document.getElementById('addProductBtn');
        const productForm = document.getElementById('productForm');
        const confirmModal = document.getElementById('confirmModal');
        let editingProduct = null;
        let entryDatePicker = null;
        let expiryDatePicker = null;


        document.addEventListener('DOMContentLoaded', function() {
            const productModal = document.getElementById('productModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            // Cerrar modal
            closeModalBtn.addEventListener('click', () => {
                productModal.style.display = 'none';
            });

            // Inicialización de flatpickr
            entryDatePicker = flatpickr("#entry_date", {
                dateFormat: "Y-m-d", // Interno: Y-m-d
                altInput: true,
                altFormat: "d/m/Y", // Visual: dd/mm/yyyy
                allowInput: true,
                onReady: function() {}
            });

            expiryDatePicker = flatpickr("#expiry_date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                allowInput: true,
                onReady: function() {}
            });


        });

        // Abrir modal de formulario
        addProductBtn.addEventListener('click', () => {
            editingProduct = null;
            productForm.reset();
            document.querySelectorAll('.error').forEach(err => err.style.display = 'none');
            productModal.style.display = 'flex';
        });



        // Obtener productos
        async function fetchProducts(page = 1) {
            const response = await fetch(`/products?page=${page}`);
            const data = await response.json();
            console.log(data)
            // Renderizar productos
            productGrid.innerHTML = '';
            data.data.forEach(product => {
                productGrid.innerHTML += `
            <div class="product-card">
                <img src="data:image/png;base64,${product.photo}" alt="${product.name}" class="product-image">
                <div class="product-details">
                    <div class="product-name">${product.name}</div>
                    <div class="product-price">Q${product.price}</div>
                </div>
                <div class="actions">
                    <span class="edit-btn" onclick="editProduct(${product.id})">✏️</span>
                    <span class="delete-btn" onclick="deleteProduct(${product.id})">🗑️</span>
                </div>
            </div>`;
            });

            // Generar paginación
            renderPagination(data);
        }

        // Guardar producto
        productForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            let valid = validateForm();
            if (!valid) return;

            const formData = new FormData(productForm);
            console.log("InfoFormulario:", formData);
            console.log(editingProduct)

            // Verificar si es una edición o creación
            let url = editingProduct ? `/products/${editingProduct}` : '/products';
            let method = editingProduct ? 'POST' : 'POST'; // Laravel no acepta PUT/PATCH con multipart/form-data directamente

            // Agregar un campo "_method" para que Laravel interprete correctamente PUT
            if (editingProduct) {
                formData.append('_method', 'PUT');
            }
            console.log(method)

            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            if (response.ok) {
                fetchProducts();
                productModal.style.display = 'none';
            }
        });

        // Validar formulario
        function validateForm() {
            let valid = true;

            // Reset de mensajes de error
            document.querySelectorAll('.error').forEach(err => err.style.display = 'none');

            // Valores del formulario
            const code = document.getElementById('product_code').value.trim();
            const name = document.getElementById('name').value.trim();
            const quantity = parseFloat(document.getElementById('quantity').value);
            const price = parseFloat(document.getElementById('price').value);
            const entryDate = entryDatePicker.input.value;
            const expiryDate = expiryDatePicker.input.value;

            // Validaciones
            if (!/^[a-zA-Z0-9]+$/.test(code)) {
                document.getElementById('codeError').style.display = 'block';
                valid = false;
            }

            if (!/^[a-zA-Z\s]+$/.test(name)) {
                document.getElementById('nameError').style.display = 'block';
                valid = false;
            }

            if (isNaN(quantity) || quantity <= 0) {
                document.getElementById('quantityError').style.display = 'block';
                valid = false;
            }

            if (isNaN(price) || price <= 0) {
                document.getElementById('priceError').style.display = 'block';
                valid = false;
            }

            if (!entryDate) {
                document.getElementById('entryDateError').style.display = 'block';
                valid = false;
            }

            if (!expiryDate || new Date(entryDate) >= new Date(expiryDate)) {
                document.getElementById('expiryDateError').style.display = 'block';
                valid = false;
            }

            return valid;
        }

        // Editar producto
        function editProduct(id) {
            editingProduct = id;
            fetch(`/products/${id}`).then(res => res.json()).then(product => {
                productForm.product_code.value = product.code;
                productForm.name.value = product.name;
                productForm.quantity.value = product.quantity;
                productForm.price.value = product.price;
                if (entryDatePicker && expiryDatePicker) { // Validar que las instancias existan
                    entryDatePicker.setDate(product.entry_date, true);
                    expiryDatePicker.setDate(product.expiry_date, true);
                } else {
                    console.error("Flatpickr no está inicializado correctamente");
                }

                productModal.style.display = 'flex';
            });
        }

        // Eliminar producto
        function deleteProduct(id) {
            confirmModal.style.display = 'flex';
            document.getElementById('confirmDeleteBtn').onclick = async () => {
                await fetch(`/products/${id}`, {
                    method: 'DELETE'
                });
                confirmModal.style.display = 'none';
                fetchProducts();
            };
            document.getElementById('cancelDeleteBtn').onclick = () => {
                confirmModal.style.display = 'none';
            };
        }

        // Renderizar botones de paginación
        function renderPagination(data) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            // Botón para ir a la página anterior
            if (data.current_page > 1) {
                paginationContainer.innerHTML += `
        <button onclick="fetchProducts(${data.current_page - 1})">Anterior</button>`;
            }

            // Botones de páginas
            for (let i = 1; i <= data.last_page; i++) {
                paginationContainer.innerHTML += `
        <button 
            onclick="fetchProducts(${i})"
            class="${i === data.current_page ? 'active' : ''}">
            ${i}
        </button>`;
            }

            // Botón para ir a la página siguiente
            if (data.current_page < data.last_page) {
                paginationContainer.innerHTML += `
        <button onclick="fetchProducts(${data.current_page + 1})">Siguiente</button>`;
            }
        }

        fetchProducts();
    </script>
</body>

</html>