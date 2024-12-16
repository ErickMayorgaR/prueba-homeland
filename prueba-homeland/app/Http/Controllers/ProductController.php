<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;



class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(8); 
        return response()->json($products, 200);
    }
    // Guardar un nuevo producto
    public function store(Request $request)
    {
        try {
            Log::info('Store Operation');
            Log::info($request);
            $request->validate([
                'product_code' => 'required|unique:products,code',
                'name' => 'required|regex:/^[a-zA-Z\s]+$/',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,png|max:1536', // 1.5 MB
                'entry_date' => 'required|date',
                'expiry_date' => 'required|date|after:entry_date',
            ]);
            Log::info('RequestValidado');

            // Convertir imagen a Base64 si existe
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageBase64 = base64_encode(file_get_contents($image));
            } else {
                $imageBase64 = null;
            }
            Log::info('Creando producto');
            $product = Product::create([
                'code' => $request->product_code,
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'photo' => $imageBase64,
                'entry_date' => $request->entry_date,
                'expiry_date' => $request->expiry_date,
            ]);

            return response()->json($product, 201);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'La validación de los datos ha fallado.',
            ], 422);
        }
    }

    // Eliminar un producto
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }


    // Obtener un producto específico por ID
    public function show($id)
    {
        $product = Product::findOrFail($id);
        Log::info('producto');


        return response()->json($product, 200);
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        try {
            Log::info("Update Operation Started");
            Log::info($request);

            // Buscar el producto
            $product = Product::findOrFail($id);

            // Validar los campos
            $request->validate([
                'product_code' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:products,code,' . $id,
                'name' => 'required|regex:/^[a-zA-Z\s]+$/',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,png|max:1536', // 1.5 MB
                'entry_date' => 'required|date',
                'expiry_date' => 'required|date|after:entry_date',
            ]);
            Log::info("Request Validated Successfully");

            // Procesar imagen si existe
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageBase64 = base64_encode(file_get_contents($image));
            }
            // Actualizar los campos
            Log::info("Updating Product Fields");
            $product->update([
                'code' => $request->product_code,
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'photo' => $imageBase64,
                'entry_date' => $request->entry_date,
                'expiry_date' => $request->expiry_date,
            ]);

            Log::info("Product Updated Successfully", ['product' => $product]);

            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'product' => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation Error", ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'La validación de los datos ha fallado.',
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error Updating Product", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado al actualizar el producto.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
