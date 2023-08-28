<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()
            ->json(File::all());
    }

    public function getUserFiles($id)
    {
        $files = File::where('user_id', $id)->get();
        return response()
            ->json($files);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($file)
    {
        if($file){
            $path = $file->store('public/files');
            $file = new File ([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);

            return $file;
        }

        return response()
            ->json([
                'message' => 'File not found in request',
            ], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        return response()
            ->json($file);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        $validated = $request->validated();

        $file->update($validated);

        return response()
            ->json([
                'message' => 'File updated successfully',
                'data' => $file
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        if($file->fileable_type == 'App\Models\User' && $file->fileable_id === auth()->user()->id){
            $file->delete();
            return response()
                ->json([
                    'message' => 'File deleted successfully'
                ], 200);
        }

        return response()
            ->json([
                'message' => 'You are not authorized to delete this file'
            ], 401);
    }

    public function attachFileToModel($file, $model_string, $model){
        $file_controller = new FileController();
        $uploaded_file = $file_controller->store($file);

        $uploaded_file->fileable_type = $model_string;
        $uploaded_file->fileable_id = $model->id;
        $uploaded_file->save();
        return $uploaded_file;
    }
}
