<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeProductJob;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'url'          => 'required|url',
            'css_selector' => 'required|string',
            'alert_below'  => 'nullable|numeric',
            'email_alert'  => 'nullable|email',
        ]);

        $product = Product::create($validated);

        ScrapeProductJob::dispatch($product);

        return redirect()->route('products.index')
                         ->with('success', 'Produto adicionado! Verificando preço...');
    }

    public function show(Product $product)
    {
        $history = $product->priceHistories()->latest('scraped_at')->get();
        return view('products.show', compact('product', 'history'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
                         ->with('success', 'Produto removido.');
    }
}