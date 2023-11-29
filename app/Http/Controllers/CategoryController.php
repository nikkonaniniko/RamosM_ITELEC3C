<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy('id')->paginate('5');
        return view('admin.category.category', compact('categories'));
    }

    public function create(Request $request) {
        $validated = $request->validate([
            'cat_name' => 'required|unique:categories|max:255',
            'image' => 'required|mimes:jpeg,png,jpg'
        ], [
            'cat_name.required' => 'Please input category name',
            'cat_name.unique' => 'Category is already existing',
            'cat_name.max' => 'Category name must be less than 255 characters',
            'image.mimes' => 'File not supported'
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $originalFileName = $uploadedFile->getClientOriginalName();

            $filename = Str::uuid() . '_' . $originalFileName;

            $imagePathLocal = $uploadedFile->storeAs('category', $originalFileName, 'public');
            $image = $originalFileName;
        }

        Category::insert([
            'cat_name' => $request->cat_name,
            'image' => $image,
            'user_id' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);

        // $image = $request->file('image');

        // $name_gen = hexdec(uniqid());
        // $img_ext = strtolower($image->getClientOriginalExtension());
        // $image_name = $name_gen . '.' . $img_ext;
        // $up_loc = 'category/';
        // $last_img = $image_name;

        // $image->move($up_loc, $image_name);

        // Category::create([
        //     'cat_name' => $request->cat_name,
        //     'image' => $last_img,
        //     'user_id' => Auth::user()->id,
        //     'created_at' => Carbon::now()
        // ]);
        
        return redirect()->back()->with('message', 'Category Created Successfully');
    }

    public function edit($id) {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    public function edit_confirm(Request $request, $id) {
        $validated = $request->validate([
            'cat_name' => 'max:255',
            'image' => 'nullable|mimes:jpeg,png,jpg'
        ], [
            'cat_name.max' => 'Category name must be less than 255 characters',
            'image.mimes' => 'File not supported'
        ]);

        $category = Category::find($id);

        if ($request->hasFile('image')) {
            $this->deleteandUploadFile($request->file('image'), $category->image);
            $category->image = $request->file('image')->getClientOriginalName();
        }

        $category->update([
            'cat_name' => $request->cat_name,
        ]);
        
        return redirect()->back()->with('message', 'Category Updated Successfully');
    }

    private function deleteAndUploadFile($uploadedFile, $existingFilePath) {
        Storage::disk('public')->delete('category/' . $existingFilePath);

        $originalFileName = $uploadedFile->getClientOriginalName();
        $uploadedFile->storeAs('category', $originalFileName, 'public');
    }

    public function delete($id) {
        $category = Category::find($id);
        $category->delete();
        return redirect()->back()->with('message', 'Category Deleted Successfully');
    }
}
