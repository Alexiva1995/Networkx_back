<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::orderBy('id','desc')->get();

        return response()->json($documents, 200);
    }

    public function store(Request $request)
    {

        $rules = [
            'document' => 'required|mimes:pdf|max:2048',
            'title' => 'required'
        ];

        $message = [
            'document.required' => 'The document is required',
            'title.required' => 'the title is required and must be a pdf file'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if( $validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $document = new Document();
        $document->title = $request->title;
        $document->description = $request->description;

        $file = $request->file('document');
        $name = str_replace(" ", "_", $file->getClientOriginalName());
        $file->move(public_path('storage/documents/'), $name);
        $document->file_name = $name;
        $document->path = 'storage/documents/'.$name;
        $document->save();

        return response()->json(['message' => 'Document registered successfully'], 200);
    }

    public function destroy(Request $request)
    {
        $document = Document::findOrFail($request->id);

        if (isset($document) ) {
            $document->delete();
            return response()->json('Document deleted successfully',200 );
        }
        return abort(404, 'No file found');
    }

    public function download(Request $request)
    {
        try {
            $document = Document::findOrFail($request->id);
            $document->downloads += 1;
            $document->save();
            $path = public_path($document->path);
            $headers = ['Content-Type' => 'application/pdf',];
            return  response()->download($path, $document->file_name, $headers);

        } catch (\Throwable $th) {
            return back()->with('warning', 'The file you want to download was not found');
        }
    }
}
