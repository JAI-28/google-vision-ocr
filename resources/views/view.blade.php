@extends('layouts.app')

@section('title', 'View Results')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@push('styles')

@endpush

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Analysis Result</h2>
        <div class="row">
            <!-- Image on the left -->
            <div class="col-md-6">
                @php
                    $extension = pathinfo($result->file_path, PATHINFO_EXTENSION);
                @endphp

                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . str_replace('public/', '', $result->file_path)) }}" 
                        alt="Analyzed Image" class="img-fluid rounded shadow">
                @elseif($extension === 'pdf')
                    <img src="{{ asset('images/pdf-placeholder.png') }}" 
                        alt="PDF Document" class="img-fluid rounded shadow">
                    <p><a href="{{ asset('storage/' . str_replace('public/', '', $result->file_path)) }}" 
                        target="_blank">View PDF</a></p>
                @else
                    <p>File format not supported.</p>
                @endif
            </div>

            <!-- Detected Text in input fields on the right -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Extracted Information</h5>
                        <form>
                            {{-- @foreach ($data as $key => $value)
                                <div class="mb-2">
                                    <label class="form-label">{{ is_string($key) ? $key : 'Additional Info' }}</label>
                                    <input type="text" class="form-control" value="{{ $value }}" readonly>
                                </div>
                            @endforeach --}}
                            {{-- @foreach(explode("\n", $result->detected_text) as $line)
                                @php
                                    $parts = explode(":", $line, 2);
                                    $key = trim($parts[0] ?? '');
                                    $value = trim($parts[1] ?? '');
                                @endphp
                                @if($key && $value)
                                    <div class="mb-2">
                                        <label class="form-label">{{ ucfirst($key) }}</label>
                                        <input type="text" class="form-control" value="{{ $value }}" readonly>
                                    </div>
                                @else
                                    <input type="text" class="form-control mb-2" value="{{ $line }}" readonly>
                                @endif
                            @endforeach --}}
                            <div class="col-md-7">
                                <h4 class="mb-3">Extracted Text</h4>
                                <div class="p-3 border rounded bg-light" style="white-space: pre-line;">
                                    {{ $result->detected_text }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="/" class="btn btn-primary mt-3">Go Back</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
