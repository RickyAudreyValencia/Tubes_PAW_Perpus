@extends('dashboard')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="register-card card p-4 mb-4">
				<div class="text-center mb-3">
					<div class="register-avatar d-inline-block mb-2" style="width:64px;height:64px;border-radius:12px;background:linear-gradient(180deg,#0d6efd,#0a58ca);display:flex;align-items:center;justify-content:center;color:white">
						<svg viewBox="0 0 24 24" width="34" height="34" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
						  <path d="M12 2l7 3v4c0 5-3 9-7 11-4-2-7-6-7-11V5l7-3z" fill="currentColor" />
						  <path d="M9.5 11.5c0-1.657 1.343-3 3-3v3h-3z" fill="#fff" opacity="0.95" />
						</svg>
					</div>
					<h3 class="register-title mb-0">Create Account</h3>
					<p class="text-muted">Join our digital library community</p>
				</div>
				<form action="{{ url('register') }}" method="POST" novalidate>
					@csrf
					<div class="mb-3">
						<input type="text" name="name" placeholder="Ricky Tamvan" value="{{ old('name') }}" class="form-control @if($errors->has('name')) is-invalid @endif"/>
						@if($errors->has('name')) <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div> @endif
					</div>
					<div class="mb-3">
						<input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" class="form-control @if($errors->has('email')) is-invalid @endif" />
						@if($errors->has('email')) <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div> @endif
					</div>
					<div class="mb-3">
						<input type="password" name="password" placeholder="Create a strong password" class="form-control @if($errors->has('password')) is-invalid @endif" />
						@if($errors->has('password')) <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div> @endif
					</div>
					<div class="mb-3">
						<input type="password" name="password_confirmation" placeholder="Confirm your password" class="form-control @if($errors->has('password_confirmation')) is-invalid @endif" />
						@if($errors->has('password_confirmation')) <div class="invalid-feedback d-block">{{ $errors->first('password_confirmation') }}</div> @endif
					</div>
					<div class="mb-3 d-flex align-items-center">
						<input type="checkbox" name="agree" value="1" class="form-check-input me-2" {{ old('agree') ? 'checked' : '' }} />
						<label class="form-check-label">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
					</div>
					<div class="d-grid mb-3"><button class="btn btn-primary">Register</button></div>

					<div class="text-center small">Already have an account? <a href="{{ url('login') }}">Login</a></div>
				</form>
			</div>
		</div>
	</div>

	@if(isset($registers) && count($registers) > 0)
		<div class="row justify-content-center">
			<div class="col-md-10">
				<div class="card p-3">
					<h5>Registered Users</h5>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Email</th>
									<th>Created</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($registers as $r)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ $r->name }}</td>
										<td>{{ $r->email }}</td>
										<td>{{ $r->created_at }}</td>
										<td>
											<a href="{{ url('register/'.$r->id.'/edit') }}" class="btn btn-sm btn-warning">Edit</a>
											<form action="{{ url('register/'.$r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-sm btn-danger">Delete</button>
											</form>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@endif
</div>
@endsection
