<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List categories with search + pagination.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $type = $request->query('type'); // income/expense filter

        $query = Category::query();

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($type) {
            $query->where('type', $type);
        }

        $categories = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('categories.index', compact('categories', 'q', 'type'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $data = $request->validate([
            'type' => 'required|in:income,expense',
            'name' => 'required|string|max:191',
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category added.');
    }

    /**
     * Edit form
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $data = $request->validate([
            'type' => 'required|in:income,expense',
            'name' => 'required|string|max:191',
        ]);

        $category->update($data);

        return back()->with('success', 'Category updated.');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return back()->with('success', 'Category removed.');
    }
}
