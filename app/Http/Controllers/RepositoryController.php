<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepositoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = $request->query('search', '');

        $query = Repository::query();

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where('file_name', 'like', '%' . $search . '%');
        }

        $repo = $query->paginate(3);
        $repo->appends($request->query());

        return view('repository.index', compact('repo', 'category', 'search'));
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('repository.create');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->search;
        $repo = Repository::where('file_name', 'like', '%' . $searchTerm . '%')->get();
        return response()->json($repo);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the incoming request for debugging purposes
        \Log::info('Incoming request data: ', $request->all());

        // Validate the incoming request
        $validated = $request->validate([
            'file_name' => 'required|min:4|string|max:255',
            'file_type' => 'required|min:5|in:document,image',
            'description' => 'nullable|string|max:1000',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,ppt,pptx,xsl,xlsx|max:2048',
            'category' => 'required|min:6|in:plant-related,flat-related',
        ]);

        // Log the validated data for debugging purposes
        \Log::info('Validated data: ', $validated);

        // Create a new repository instance with the validated data
        $repository = new Repository([
            'file_name' => $validated['file_name'],
            'file_type' => $validated['file_type'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'uploaded_by' => auth()->user()->id,
        ]);

        // Check if a file is uploaded
        if ($request->hasFile('file_path')) {
            \Log::info('File is present in the request.');

            $file = $request->file('file_path');
            $name = time() . '.' . $file->getClientOriginalExtension();
            \Log::info('Uploaded file details:', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            if ($validated['file_type'] === 'image'){
                $storagePath = 'images';
            }
            else{
                $storagePath = 'documents';
            }

            \Log::info('Determined storage path: ' . $storagePath);

            $filePath = $file->storeAs($storagePath, $name, 'public');
            \Log::info('Stored file path: ' . $filePath);

            // Set the file_path attribute to the stored file path
            $repository['file_path'] = '/storage/' . $filePath;
        } else {
            \Log::info('No file present in the request.');
        }

        // Save the repository record and return a response
        if ($repository->save()) {
            \Log::info('Repository record saved successfully.');
            return redirect()->route('repository.index')->with('toastr', json_encode([
                'type' => 'success',
                'message' => 'New record added successfully'
            ]));
        } else {
            \Log::error('Failed to save repository record.');
            return redirect()->back()->withInput()->with('toastr', json_encode([
                'type' => 'error',
                'message' => 'Failed to add new record'
            ]));
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(Repository $repository)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repository $repository)
    {
        return view('repository.edit',compact('repository'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repository $repository)
    {


        // Validate the incoming request
        $validated = $request->validate([
            'file_name' => 'min:4|string|max:255',
            'file_type' => 'min:5|in:document,image',
            'description' => 'nullable|string|max:1000',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,ppt,pptx,xsl,xlsx|max:2048',
            'category' => 'min:6|in:plant-related,flat-related',
        ]);

        // Log the validated data for debugging purposes
        \Log::info('Validated data: ', $validated);

        // Create a new repository instance with the validated data
        $repository->update($validated);

        // Check if a file is uploaded
        if ($request->hasFile('file_path')) {

            $file = $request->file('file_path');
            $name = time() . '.' . $file->getClientOriginalExtension();

            if ($validated['file_type'] === 'image'){
                $storagePath = 'images';
            }
            else{
                $storagePath = 'documents';
            }


            $filePath = $file->storeAs($storagePath, $name, 'public');

            // Set the file_path attribute to the stored file path
            $repository['file_path'] = '/storage/' . $filePath;
        }

        // Save the repository record and return a response
        if ($repository->update($validated)) {
            return redirect()->route('repository.index')->with('toastr', json_encode([
                'type' => 'success',
                'message' => 'Record has been updated successfully'
            ]));
        } else {

            return redirect()->back()->withInput()->with('toastr', json_encode([
                'type' => 'error',
                'message' => 'Failed to update new record'
            ]));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repository $repository)
    {
        $repository->delete();
        return redirect()->route('repository.index');
    }
}
