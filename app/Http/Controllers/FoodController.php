<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Http\Resources\FoodResource;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        return  FoodResource::collection(Food::paginate(6)->items());       
    }
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'photo' => 'required|file',
        ]);

        $path = $this->uploadImage($request); 
        $food = Food::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'photo' => $path,
        ]);

        return new FoodResource($food);
    }

    public function show($id)
    {
        $food = Food::findOrFail($id);

        return new FoodResource($food);
    }

    public function update(FoodRequest $request, $id)
    {
        $food = Food::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            $this->deleteImage($food->photo);

            $path = $this->uploadImage($request);
            $food->photo = $path;
        }
        if ($request->has('name')) {
          
            $food->name = $request->name;
        }
        if ($request->has('description')) {
          
            $food->description = $request->description;
        }
       
        $food->save();
        return new FoodResource($food);
    } 
    public function destroy($id)
    {
        $food = Food::findOrFail($id);

        $this->deleteImage($food->photo);

        $food->delete();

        return response()->json([
            'message' => 'Food deleted successfully.',
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