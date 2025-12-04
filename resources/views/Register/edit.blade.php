@extends('dashboard')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px)">
	<div class="register-page" style="width:100%; max-width:520px; padding:20px;">
		<div class="register-card card p-4">
			<div class="text-center mb-3">
				<div class="register-avatar d-inline-block mb-2" style="width:64px;height:64px;border-radius:12px;background:linear-gradient(180deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:white">
					<svg viewBox="0 0 24 24" width="34" height="34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
					  <path d="M12 2l7 3v4c0 5-3 9-7 11-4-2-7-6-7-11V5l7-3z" fill="currentColor" />
					  <path d="M9.5 11.5c0-1.657 1.343-3 3-3v3h-3z" fill="#fff" opacity="0.95" />
					</svg>
				</div>
				<h3 class="register-title mb-0">Edit Account</h3>
				<p class="text-muted">Update your account information</p>
			</div>

			<form action="{{ url('register/'.($register->id ?? '')) }}" method="POST" novalidate>
				@csrf
				@method('PUT')
				<div class="mb-3">
					<label class="form-label">Full Name</label>
					<div class="input-field">
						<div class="input-wrap d-flex align-items-center">
							<div class="input-icon me-2" aria-hidden>
							  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
								<circle cx="12" cy="8" r="3" stroke="currentColor" stroke-width="1.25" fill="none" />
								<path d="M6 20c0-3 3-5 6-5s6 2 6 5" stroke="currentColor" stroke-width="1.25" fill="none" stroke-linecap="round" />
							  </svg>
							</div>
							<input type="text" name="name" class="form-control @if($errors->has('name')) is-invalid @endif" placeholder="Ricky Tamvan" value="{{ old('name', $register->name ?? '') }}" />
						</div>
					</div>
					@if($errors->has('name')) <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div> @endif
				</div>

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
							<input type="email" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="you@example.com" value="{{ old('email', $register->email ?? '') }}" />
						</div>
					</div>
					@if($errors->has('email')) <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div> @endif
				</div>

				<div class="mb-3">
					<label class="form-label">Password (leave blank to keep current)</label>
					<div class="input-field">
						<div class="input-wrap d-flex align-items-center">
							<div class="input-icon me-2" aria-hidden>
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
								  <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.25" fill="none" />
								  <path d="M8 11V8a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.25" fill="none" stroke-linecap="round" />
								</svg>
							</div>
							<input type="password" name="password" class="form-control @if($errors->has('password')) is-invalid @endif" placeholder="Create a strong password" />
						</div>
					</div>
					@if($errors->has('password')) <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div> @endif
				</div>

				<div class="mb-3">
					<label class="form-label">Confirm Password</label>
					<div class="input-field">
						<div class="input-wrap d-flex align-items-center">
							<div class="input-icon me-2" aria-hidden>
								<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
								  <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.25" fill="none" />
								  <path d="M8 11V8a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.25" fill="none" stroke-linecap="round" />
								</svg>
							</div>
							<input type="password" name="password_confirmation" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" placeholder="Confirm your password" />
						</div>
					</div>
					@if($errors->has('password_confirmation')) <div class="invalid-feedback d-block">{{ $errors->first('password_confirmation') }}</div> @endif
				</div>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary">Update Account</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
