<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummerNoteController extends Controller
{
    //summer note image
    public function summerUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Define the folder where you want to store the images
            $folder = '/uploads/images/summernote/';

            // Generate a unique filename for the image
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the specified folder
            //$image->storeAs($folder, $filename);

            $image->move(public_path('/uploads/images/summernote/'),$filename);
            //$data['image'] = $imageName;

            // Get the URL of the uploaded image
            $imageUrl = asset($folder . $filename);

            return response()->json(['url' => $imageUrl]);
        }

        return response()->json(['error' => 'Image not found.'], 404);
    }


    public function summerDelete(Request $request)
    {
        $imageSrc = $request->input('imageSrc');

        // Extract the filename from the image URL
        $filename = basename(parse_url($imageSrc, PHP_URL_PATH));


        // Define the folder where the images are stored
       // $folder = '/uploads/images/summernote/';
        $destination = public_path('uploads/images/summernote/').$filename;

        // Delete the image from the server folder
        if (\File::exists($destination)) {
            \File::delete($destination);
            return response()->json(['message' => 'Image deleted successfully']);
        } else {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json(['message' => 'Image deleted successfully']);
    }
}
