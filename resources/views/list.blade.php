@extends('layouts.app')

@section('title', 'List Results')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@push('styles')

@endpush

@section('content')
<div class="container">
    <h4 class="mb-3">OCR Results</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>File</th>
                    <th>Extracted Text</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                <tr>
                    <td>
                        @php
                            $extension = pathinfo($result->file_path, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                            <img src="{{ asset('storage/' . str_replace('public/', '', $result->file_path)) }}" 
                                 alt="Analyzed Image" width="100">
                        @elseif($extension === 'pdf')
                            <img src="{{ asset('images/pdf-placeholder.png') }}" 
                                 alt="PDF Document" width="50">
                        @endif
                    </td>
                    <td>{{ Str::limit($result->detected_text, 100) }}</td>
                    <td>
                        <a href="{{ route('google.result', $result->id) }}" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    {{ $results->links() }}
</div>
@endsection

@push('scripts')

@endpush
