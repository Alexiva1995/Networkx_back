<?php

namespace App\Http\Controllers;

use App\Models\Learning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Document;

class LearningController extends Controller
{
    public function learnings () {
        $learning = Learning::all();
        return response()->json($learning, 200);
    }
    public function deleteLearning (Request $request) {
        try {
            $learning = Learning::find($request->id);
            $learning->delete();
            return response()->json($learning, 200);
        } catch (\Throwable $th) {
            return response()->json($learning, 400);
        }
    }
    // public function editLearning (Request $request) {
    //     $rules = [
    //         'id' => 'required',
    //         'title' => 'required',
    //         'description' => 'required'
    //     ];

    //     $message = [
    //         'title.required' => 'The title is required and must be a pdf file',
    //         'description.required' => 'The description is required and must be a pdf file',
    //     ];
    //     $validator = Validator::make($request->all(), $rules, $message);
    //     try {
    //         $learning = Learning::find($request->id);
    //         $learning->title = $request->title;
    //         $learning->description = $request->description;
            
    //         return response()->json($learning, 200);
    //     } catch (\Throwable $th) {
    //         return response()->json($learning, 400);
    //     }
    // }
    public function videos () {
        $learning = Learning::where('type', '1')->get();
        return response()->json($learning, 200);
    }
    public function links () {
        $learning = Learning::where('type', '2')->get();
        return response()->json($learning, 200);
    }
    public function documents () {
        $learning = Learning::where('type', '0')->get();
        return response()->json($learning, 200);
    }
    public function documentStore(Request $request) {
        $rules = [
            'document' => 'required|mimes:pdf|max:2048',
            'title' => 'required',
            'description' => 'required'
        ];

        $message = [
            'document.required' => 'The document is required',
            'title.required' => 'The title is required and must be a pdf file',
            'description.required' => 'The description is required and must be a pdf file',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if( $validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $document = new Learning();
        $document->title = $request->title;
        $document->description = $request->description;

        $file = $request->file('document');
        $name = str_replace(" ", "_", $file->getClientOriginalName());
        $file->move(public_path('storage/documents/'), $name);
        $document->file_name = $name;
        $document->path = 'storage/documents/'.$name;
        $document->type = 0;
        $document->save();

        return response()->json(['message' => 'Document registered successfully'], 200);
    }
    public function videoStore(Request $request) {
        $rules = [
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:20000',
            'title' => 'required',
            'description' => 'required'
        ];

        $message = [
            'video.required' => 'The video is required',
            'video.max' => 'The selected video is too heavy',
            'title.required' => 'the title is required and must be a pdf file',
            'description.required' => 'The description is required and must be a pdf file',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if( $validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $document = new Learning();
        $document->title = $request->title;
        $document->description = $request->description;

        $file = $request->file('video');
        $name = str_replace(" ", "_", $file->getClientOriginalName());
        $file->move(public_path('storage/video/'), $name);
        $document->file_name = $name;
        $document->path = 'storage/video/'.$name;
        $document->type = 1;
        $document->save();

        return response()->json(['message' => 'Video registered successfully'], 200);
    }
    public function linkStore(Request $request) {
        $rules = [
            'link' => 'required',
            'title' => 'required',
            'description' => 'required'
        ];

        $message = [
            'link.required' => 'The link is required',
            'title.required' => 'the title is required and must be a pdf file',
            'description.required' => 'The description is required and must be a pdf file',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if( $validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $document = new Learning();
        $document->title = $request->title;
        $document->description = $request->description;
        $document->file_name = $request->link;
        $document->path = null;
        $document->type = 2;
        $document->save();
        return response()->json(['message' => 'Link registered successfully'], 200);
    }
    public function download(Request $request)
    {
        try {
            $document = Learning::findOrFail($request->id);
            $path = public_path($document->path);
            return  response()->download($path, $document->file_name);

        } catch (\Throwable $th) {
            return back()->with('warning', 'The file you want to download was not found');
        }
    }
}
