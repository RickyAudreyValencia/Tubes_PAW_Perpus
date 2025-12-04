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
				<h3 class="login-title mb-0">Edit Account</h3>
				<div class="text-muted">Update account credentials</div>
			</div>

			<form action="{{ url('login/'.($login->id ?? '')) }}" method="POST" novalidate>
				@csrf
				@method('PUT')
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
							<input type="email" name="email" placeholder="you@example.com" value="{{ old('email', $login->email ?? '') }}" class="form-control @if($errors->has('email')) is-invalid @endif" />
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

				<div class="mb-3 text-end">
					<button type="submit" class="btn btn-primary">Update Account</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
