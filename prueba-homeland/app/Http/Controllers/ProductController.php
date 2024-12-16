<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($products);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products|regex:/^[a-zA-Z0-9]+$/',
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'entry_date' => 'required|date|date_format:Y-m-d',
            'expiry_date' => 'required|date|after:entry_date',
            'image' => 'required|mimes:jpg,png|max:1536', // 1.5MB
        ]);
    
        $path = $request->file('image')->store('images', 'public');
    
        Product::create([
            'product_code' => $validated['product_code'],
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'entry_date' => $validated['entry_date'],
            'expiry_date' => $validated['expiry_date'],
            'image_path' => $path,
        ]);
    
        return response()->json(['success' => true]);
    }
    
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }
    


}
