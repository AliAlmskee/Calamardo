<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use Illuminate\Http\Request;
use App\Http\Resources\DrinkResource;
use App\Http\Requests\DrinkRequest;

class DrinkController extends Controller
{
    public function index()
    {
        return DrinkResource::collection(Drink::paginate(6)->items());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'photo' => 'required|file',
            'price' => 'required|numeric',
        ]);

        $path = $this->uploadImage($request);
        $drink = Drink::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'photo' => $path,
            'price' => $validatedData['price'],
        ]);

        return new DrinkResource($drink);
    }

    public function show($id)
    {
        $drink = Drink::findOrFail($id);

        return new DrinkResource($drink);
    }

    public function update(DrinkRequest $request, $id)
    {
        $drink = Drink::findOrFail($id);

        if ($request->hasFile('photo')) {
            $this->deleteImage($drink->photo);

            $path = $this->uploadImage($request);
            $drink->photo = $path;
        }

        if ($request->has('name')) {
            $drink->name = $request->name;
        }

        if ($request->has('description')) {
            $drink->description = $request->description;
        }

        if ($request->has('price')) {
            $decimalPrice =$request->price;
          
            $drink->price = $decimalPrice;       
                 }

        $drink->save();
        return new DrinkResource($drink);
    }

    public function destroy($id)
    {
        $drink = Drink::findOrFail($id);

        $this->deleteImage($drink->photo);

        $drink->delete();

        return response()->json([
            'message' => 'Drink deleted successfully.',
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'photo' => 'required|file|max:5000',
        ]);

        $photo = $request->file('photo');
        $newFileName = time() . '_' . $photo->getClientOriginalName();
        $filePath = '';

        if (strpos($photo->getMimeType(), 'image') === 0) {
            $photo->move(public_path('images'), $newFileName);
            $filePath = $newFileName;
        } else {
            return response()->json('Uploaded file is not an image');
        }

        return $filePath;
    }

    public function deleteImage($path)
    {
        $imagePath = public_path('images/' . $path);
        if (file_exists($imagePath)) {
            unlink($imagePath);
            return response()->json('Image file deleted successfully');
        }

        return response()->json('Image not found');
    }

    public function getImage($path)
    {
        $imagePath = public_path('images/' . $path);
        if (file_exists($imagePath)) {
            return response()->file($imagePath);
        }

        return response()->json('Image not found');
    }
}
