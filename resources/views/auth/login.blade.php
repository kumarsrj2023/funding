@extends('auth.index')
@section('title', Helper::getSiteTitle('Login'))

@section('content')
<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-lg-10">
                <div class="card overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <div class="auth-brand p-4">
                                    <a href="{{ route('home') }}" class="logo-light">
                                        <img src="{{ asset('images/logo.svg') }}" alt="logo">
                                    </a>
                                    <a href="{{ route('home') }}" class="logo-dark">
                                        <img src="{{ asset('images/logo.svg') }}" alt="dark logo" height="22">
                                    </a>
                                </div>
                                <div class="p-4 my-auto mb-4">
                                    <h4 class="fs-20">Sign In</h4>
                                    <p class="text-muted mb-3">Enter your email address and password to access dashboard.</p>
                                    <form action="{{ URL::current() }}" method="post" enctype="multipart/form-data">
                                    	@csrf
                                    	@include('includes.show-message', ['extra_class' => 'mb-3'])
                                        <div class="mb-3">
			                                @php $index = 'email'; @endphp
			                                <label class="form-label" for="{{ $index }}">Email Address / Username </label>
			                                <input type="text" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" value="{{ Helper::getInputValue($index, isset($data) && !empty($data) ? $data : '') }}" placeholder="Enter your email address">
			                                @if ($errors->has($index))
			                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
			                                @endif
			                            </div>
                                        <div class="mb-3">
			                                @php $index = 'password'; @endphp
			                                <label class="form-label" for="{{ $index }}">Password</label>
			                                <input type="password" class="form-control {{ $index }} {{ $errors->has($index) ? 'is-invalid' : '' }}" id="{{ $index }}" name="{{ $index }}" placeholder="Enter your password">
			                                @if ($errors->has($index))
			                                    <div class="invalid-feedback">{{ $errors->first($index) }}</div>
			                                @endif
			                            </div>
                                        <div class="mb-3">
                                            <div class="form-check form-checkbox-dark">
                                                <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" value="1">
                                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="mb-0 text-start">
                                            <button class="btn btn-dark btn-show-processing me-1" type="submit">
						                        <span class="spinner-border spinner-border-sm processing-show d-none me-1" role="status" aria-hidden="true"></span>
						                        <span class="processing-show d-none">Logging In...</span>
						                        <span class="default-show">Log In</span>
						                    </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection