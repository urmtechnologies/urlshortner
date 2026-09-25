@extends('layout.index')
@section('title', 'Generate Short URL')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <h4 class="fw-bold mb-4">Generate Short URL</h4>
            <div class="card" style="max-width:750px">
                <div class="card-body">
                    @include('partials.alerts')
                    <form method="POST" action="{{ route(auth()->user()->role . '.short-urls.store') }}">@csrf
                        <label class="form-label" for="original_url">Long URL <span class="text-danger">*</span></label>
                        <input class="form-control @error('original_url') is-invalid @enderror" id="original_url"
                            type="url" name="original_url" maxlength="2048" value="{{ old('original_url') }}"
                            placeholder="https://example.com/a-long-page" required>
                        @error('original_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button class="btn btn-primary mt-3" type="submit">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
