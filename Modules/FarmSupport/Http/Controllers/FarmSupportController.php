<?php

namespace Modules\FarmSupport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Farmer;
use Modules\FarmSupport\Models\FarmSupport;
use Modules\FarmSupport\Models\SupportedProduct;

class FarmSupportController extends Controller
{
    public function index()
    {
        $farmers = Farmer::all();
        $products = SupportedProduct::all();
        $supports = FarmSupport::with('farmer', 'products')->get();
        return view('farmsupport::index', compact('supports', 'farmers', 'products'));
    }

    public function create()
    {
        $farmers = Farmer::all();
        $products = SupportedProduct::all();
        return view('farmsupport::create', compact('farmers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'description' => 'required|string',
            'products' => 'required|array',
            'products.*' => 'exists:supported_products,id',
        ]);

        $support = FarmSupport::create($request->only('farmer_id', 'name', 'description'));
        $support->products()->sync($request->products);

        return redirect()->route('farmsupport.index')->with('success', 'Support created successfully.');
    }

    /**
     * Store a newly created supported product in storage.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        SupportedProduct::create([
            'name' => $request->input('name')
        ]);
        return redirect()->route('farmsupport.index')->with('success', 'Product added successfully.');
    }

    public function show($id)
    {
        $support = FarmSupport::with('farmer', 'products')->findOrFail($id);
        return view('farmsupport::show', compact('support'));
    }

    public function edit($id)
    {
        $support = FarmSupport::with('products')->findOrFail($id);
        $farmers = Farmer::all();
        $products = SupportedProduct::all();
        return view('farmsupport::edit', compact('support', 'farmers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'description' => 'required|string',
            'products' => 'required|array',
            'products.*' => 'exists:supported_products,id',
        ]);

        $support = FarmSupport::findOrFail($id);
        $support->update($request->only('farmer_id', 'name', 'description'));
        $support->products()->sync($request->products);

        return redirect()->route('farmsupport.index')->with('success', 'Support updated successfully.');
    }
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $supportedProductInput = SupportedProduct::findOrFail($id);
        $supportedProductInput->update($request->only('name'));
        return redirect()->route('farmsupport.index')->with('success', 'Support product input updated successfully.');
    }

    public function destroy($id)
    {
        $support = FarmSupport::findOrFail($id);
        $support->delete();

        return redirect()->route('farmsupport.index')->with('success', 'Support deleted successfully.');
    }

    public function destroyProduct($id)
    {
        $supportedProductInput = SupportedProduct::findOrFail($id);
        $supportedProductInput->delete();

        return redirect()->route('farmsupport.index')->with('success', 'Product input deleted successfully.');
    }
}
