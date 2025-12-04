@extends('dashboard')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px)">
	<div class="login-page" style="width:100%; max-width:420px; padding:20px;">
		<div class="login-card card p-4">
			<div class="text-center mb-3">
				<div class="login-avatar d-inline-block mb-2" style="width:64px;height:64px;border-radius:12px;background:linear-gradient(180deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:white">
					<svg viewBox="0 0 24 24" width="34" height="34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
					  <path d="M6 2h12v4l-6 4-6-4V2z" fill="var(--library-accent)" stroke="none" />
					  <path d="M6 6v14h12V6" fill="#fff" opacity="0.9" />
					</svg>
				</div>
				<h3 class="login-title mb-0">Welcome Back</h3>
				<div class="text-muted">Login to access your digital library</div>
			</div>

			<form action="{{ url('login') }}" method="POST" novalidate>
				@csrf
				<div class="mb-3">
					<label class="form-label">Email</label>
					<div class="input-field">
						<div class="input-wrap d-flex align-items-center">
							<div class="input-icon me-2" aria-hidden>
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
								  <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.25" fill="none" />
								  <path d="M3 7L12 13L21 7" stroke="currentColor" stroke-width="1.25" fill="none" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</div>
							<input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" class="form-control @if($errors->has('email')) is-invalid @endif" />
						</div>
					</div>
					@if($errors->has('email'))
						<div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
					@endif
				</div>

				<div class="mb-3">
					<label class="form-label">Password</label>
					<div class="input-field">
						<div class="input-wrap d-flex align-items-center">
							<div class="input-icon me-2" aria-hidden>
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
								  <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.25" fill="none" />
								  <path d="M8 11V8a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.25" fill="none" stroke-linecap="round" />
								</svg>
							</div>
							<input type="password" name="password" placeholder="Enter your password" class="form-control @if($errors->has('password')) is-invalid @endif" />
						</div>
					</div>
					@if($errors->has('password'))
						<div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
					@endif
				</div>

				<div class="d-flex justify-content-between align-items-center mb-3">
					<div>
						<label class="form-check-label me-2">
							<input class="form-check-input me-1" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} /> Remember me
						</label>
					</div>
					<div>
						<a href="#" class="text-decoration-none">Forgot password?</a>
					</div>
				</div>

				<div class="d-grid mb-3">
					<button type="submit" class="btn btn-primary">Login to Account →</button>
				</div>

				<div class="text-center small text-muted mb-3">Or continue with</div>

				<div class="d-flex gap-2 mb-3">
					<button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
						<img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" width="20" height="20" class="me-2" />
						Google
					</button>
					<button type="button" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
						<svg viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="me-2">
						  <path fill="#1877f2" d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24h11.5v-9.294H9.692v-3.622h3.133V9.412c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.465h-1.26c-1.242 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.917h-2.33V24H24C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z"/>
						</svg>
						Facebook
					</button>
				</div>

				<div class="text-center small">Don't have an account? <a href="{{ url('register') }}">Sign up for free</a></div>
			</form>
		</div>
	</div>
</div>
@endsection
