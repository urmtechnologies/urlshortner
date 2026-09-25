<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | Sembark URL Shortener</title><link rel="icon" href="{{ asset('assets/img/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"><link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/simplebar/simplebar.min.css') }}"><link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<style>.content{min-height:calc(100vh - 80px)}.table td,.table th{vertical-align:middle}.navbar-header form{margin:0}</style>
</head><body><div class="main-wrapper"><header class="navbar-header"><div class="page-container topbar-menu">
<div class="d-flex align-items-center gap-3"><a id="mobile_btn" class="mobile-btn" href="#sidebar" aria-label="Open menu"><i class="ti ti-menu-deep fs-24"></i></a>
<a href="{{ route(auth()->user()->role.'.dashboard') }}" class="logo"><span class="logo-light"><span class="logo-lg"><img width="145" src="{{ asset('assets/img/logo.png') }}" alt="Sembark"></span></span><span class="logo-dark"><span class="logo-lg"><img width="145" src="{{ asset('assets/img/logo.png') }}" alt="Sembark"></span></span></a>
<button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn2" type="button" aria-label="Toggle sidebar"><i class="ti ti-arrow-bar-to-right"></i></button>
<a class="text-dark fw-semibold" href="{{ route(auth()->user()->role.'.dashboard') }}">Dashboard</a></div>
<div class="d-flex align-items-center gap-3"><span class="d-none d-sm-inline text-muted">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="btn btn-sm btn-light border"><i class="ti ti-logout me-1"></i> Logout</button></form></div>
</div></header>
@include('layout.sidebar.superadmin')
@yield('content')
</div><script src="{{ asset('assets/js/jquery.min.js') }}"></script><script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script><script src="{{ asset('assets/plugins/simplebar/simplebar.min.js') }}"></script><script src="{{ asset('assets/js/script.js') }}"></script>
<script>document.addEventListener('click',async function(e){const b=e.target.closest('.copy-link');if(!b)return;try{await navigator.clipboard.writeText(b.dataset.url);b.textContent='Copied';setTimeout(()=>b.textContent='Copy',1800)}catch(err){alert('Copy failed. Please select the URL manually.')}});</script>
</body></html>
