<?php

namespace App\Http\Controllers;

use App\Models\Google;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Vision\V1\AnnotateFileRequest;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\AsyncAnnotateFileRequest;
use Google\Cloud\Vision\V1\AsyncBatchAnnotateFilesRequest;
use Google\Cloud\Vision\V1\BatchAnnotateFilesRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\GcsSource;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\ImageContext;
use Google\Cloud\Vision\V1\InputConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Google $google)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Google $google)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Google $google)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Google $google)
    {
        //
    }

    public function analyzeFile(Request $request)
    {
        function uploadToGCS($file)
        {
            $storage = new StorageClient([
                'keyFilePath' => storage_path('app/visionkey.json'),
            ]);
    
            $bucketName = 'pomtech_ocr_bucket';
            $bucket = $storage->bucket($bucketName);
    
            $fileName = 'uploads/' . time() . '_' . $file->getClientOriginalName();
    
            $object = $bucket->upload(
                fopen($file->getPathname(), 'r'),
                ['name' => $fileName] 
            );
    
            return "gs://$bucketName/$fileName";
        }
        $request->validate([
            'file' => 'required|mimes:jpeg,png,pdf|max:10240',
        ]);

        $file = $request->file('file'); 
        $filePath = $file->store('uploads', 'public');
        $fullPath = Storage::path('/public/'. $filePath);

        $imageAnnotator = new ImageAnnotatorClient([
            'credentials' => storage_path('app/visionkey.json'),
        ]);
        $content = file_get_contents($fullPath);

        if ($file->getClientOriginalExtension() === 'pdf') {
            $gcsUri = uploadToGCS($file);
            $inputConfig = (new InputConfig())
                ->setGcsSource((new GcsSource())->setUri($gcsUri))
                ->setMimeType('application/pdf');

            $feature = (new Feature())->setType(Feature\Type::DOCUMENT_TEXT_DETECTION);

            $fileRequest = (new AnnotateFileRequest())
                ->setInputConfig($inputConfig)
                ->setFeatures([$feature]);

            $batchRequest = (new BatchAnnotateFilesRequest())->setRequests([$fileRequest]);

            $response = $imageAnnotator->batchAnnotateFiles($batchRequest);
            // $textAnnotations = $response->getResponses()[0];
            // dd($textAnnotations->getResponses()[0]->getContext());
            // if ($textAnnotations) {
            //     $text = $textAnnotations->getText();
            //     return response()->json(['text' => $text]);
            // }
            $fileResponse = $response->getResponses()[0]; // First file response

if ($fileResponse->getResponses()) {
    $annotationResponse = $fileResponse->getResponses()[0]; // First annotation response

    if ($annotationResponse->hasFullTextAnnotation()) {
        $fullTextAnnotation = $annotationResponse->getFullTextAnnotation();
        $text = $fullTextAnnotation->getText(); // Extract text

        return response()->json(['text' => $text]);
    }
}
        } else {
            $image = (new Image())->setContent($content);

            $feature = (new Feature())->setType(Feature\Type::TEXT_DETECTION);
            $imageContext = (new ImageContext())->setLanguageHints(['en']);
            $imageRequests = (new AnnotateImageRequest())
                ->setFeatures([$feature])->setImageContext($imageContext)->setImage($image);

            $batchRequest = (new BatchAnnotateImagesRequest())->setRequests([$imageRequests]);

            $response = $imageAnnotator->batchAnnotateImages($batchRequest);
            $textAnnotations = $response->getResponses()[0]->getTextAnnotations();

            if ($textAnnotations) {
    
                $detectedText = $textAnnotations[0]->getDescription();
    
                return response()->json(['detectedText' => $detectedText]);
    
            } else {
    
                return response()->json(['message' => 'No text detected'], 404);
    
            }
        }
        return response()->json(['text' => 'not found']);
    }
}
