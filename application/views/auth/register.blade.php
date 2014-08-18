@layout('layout')

@section('content')
<h1>Sign up</h1>

{{ Form::open('register', 'POST', array('class' => 'form-horizontal')) }}

	<fieldset>

		<!-- username field -->
		<div class="form-group">
			{{ Form::label('username', 'Username', array('class' => 'control-label col-xs-2', 'for' => 'username')) }}
			<div class="col-xs-10">
				<div class="input-group">
					{{ Form::text('username', Input::old('username')) }}
				</div>
			</div>
		</div>

		<!-- email -->
		<div class="form-group">
			{{ Form::label('email', 'E-mail', array('class' => 'control-label col-xs-2', 'for' => 'email')) }}
			<div class="col-xs-10">
				<div class="input-group">
					{{ Form::text('email') }}
				</div>
			</div>
		</div>

		<!-- password field -->
		<div class="form-group">
			{{ Form::label('password', 'Password', array('class' => 'control-label col-xs-2', 'for' => 'password')) }}
			<div class="col-xs-10">
				<div class="input-group">
					{{ Form::password('password') }}
				</div>
			</div>
		</div>

		<!-- repeat password field -->
		<div class="form-group">
			{{ Form::label('password2', 'Repeat Password', array('class' => 'control-label col-xs-2', 'for' => 'password2')) }}
			<div class="col-xs-10">
				<div class="input-group">
					{{ Form::password('password2') }}
				</div>
			</div>
		</div>

		<!-- Name -->
		<div class="form-group">
			{{ Form::label('name', 'Name', array('class' => 'control-label col-xs-2', 'for' => 'name')) }}
			<div class="col-xs-10">
				<div class="input-group">
					{{ Form::text('name') }}
				</div>
			</div>
		</div>

		<!-- csrf token	 -->
		{{ Form::token() }}

		<!-- submit button -->
		<div class="form-group">
			<div class="col-xs-offset-2 col-xs-10">
				<div class="input-group">
					{{ Form::submit('Register', array('class '=> "btn my-btn")) }}
				</div>
			</div>
		</div>

	</fieldset>
{{ Form::close() }}
@endsection
