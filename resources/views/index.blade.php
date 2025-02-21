@extends('layouts.app')

@section('title', 'Ocr Documents')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@push('styles')
    <style>
        #kt_dropzonejs_example_1.dropzone {
            border: 2px dashed #3498db;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background-color: #fff;
            cursor: pointer;
            transition: 0.3s;
        }
        #kt_dropzonejs_example_1.dropzone:hover {
            background-color: #ecf6ff;
        }
        #kt_dropzonejs_example_1.dropzone.dragover {
            background-color: #d6eaff;
            border-color: #2980b9;
        }
        .file-info {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">  
            <div class="col-md-12">
                <h1 class="fw-bolder text-center" style="font-size:42px;color:#33333b;">Upload files for Ocr</h1>
            </div>
            <div class="col-md-12">
                <h1 class="fw-normal text-center" style="font-size:22px;color:#47474f;">Upload pdf or image for recognition</h1>
            </div>
        </div>
        <div class="row text-center m-4">
            <div class="col-md-12">
                <form class="form" action="#" method="post">
                    <div class="fv-row">
                        <div class="dropzone" id="kt_dropzonejs_example_1">
                            <div class="dz-message needsclick">
                                <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                <div class="ms-4">
                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
        <script>
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
                url: "{{env('API_URL')}}/analyze",
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
                paramName: "file",
                maxFiles: 10,
                maxFilesize: 10,
                addRemoveLinks: true,
                accept: function(file, done) {
                    if (file.name == "wow.jpg") {
                        done("Naha, you don't.");
                    } else {
                        done();
                    }
                },
                success: function(file, response) {
                    if (response.Google) {
                        const id = response.Google.id;
                        window.location.href = `/result/${id}`;
                    }
                }
            });
        </script>
@endpush
