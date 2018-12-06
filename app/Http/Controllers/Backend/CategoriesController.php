<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Requests\CategoryDestroyRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends BackendController
{
    public function index()
    {
        $categories      = Category::with('posts')->orderBy('title')->paginate($this->limit);
        $categoriesCount = Category::count();

        return view("backend.categories.index", compact('categories', 'categoriesCount'));
    }

    public function create()
    {
        $category = new Category();
        return view("backend.categories.create", compact('category'));
    }


    public function store(CategoryStoreRequest $request)
    {
        #---we call Category create method, and past the data from the request object
        Category::create($request->all());

        #--- redirect to the Category Index Page
        return redirect("/backend/categories")
            ->with("message", "New category was created successfully!");
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view("backend.categories.edit", compact('category'));
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        Category::findOrFail($id)->update($request->all());

        return redirect("/backend/categories")
            ->with("message", "Category was updated successfully!");
    }

    public function destroy(CategoryDestroyRequest $request, $id)
    {
        Post::withTrashed()->where('category_id', $id)
            ->update(['category_id' => config('cms.default_category_id')]);

        $category = Category::findOrFail($id);
        $category->delete();

        return redirect("/backend/categories")
            ->with("message", "Category was deleted successfully!");
    }

}
