@extends('layout.index')
@section('title', 'Member Dashboard')
@section('content')<div class="page-wrapper">
        <div class="content">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Member Dashboard</h4>
                    <p class="text-muted mb-0">Your generated short URLs</p>
                </div><a class="btn btn-primary" href="{{ route('member.short-urls.create') }}">Generate URL</a>
            </div>
            @include('partials.alerts')
            @include('partials.recent-urls')
        </div>
</div>@endsection
