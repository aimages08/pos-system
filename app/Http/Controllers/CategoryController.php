<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  

    // index page view all
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }


    // Create 
      public function create()
    {
        return view('categories.create');
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,disabled',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect('/categories')->with('success', 'Category added successfully.');
    }


        // Edit - show form
        public function edit($id)
        {
            $category = Category::findOrFail($id);
            return view('categories.edit', compact('category'));
        }

        // Update - save changes
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:active,disabled',
            ]);

            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            return redirect('/categories')->with('success', 'Category updated successfully.');
        }


    // Delete
    public function destroy($id)
        {
            Category::findOrFail($id)->delete();

            return redirect('/categories')->with('success', 'Category deleted successfully.');
        }


}