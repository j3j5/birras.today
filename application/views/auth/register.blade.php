@layout('layout')

@section('content')
<h1>Sign up</h1>

{{ Form::open('register', 'POST') }}

<!-- username field -->
<p>
	{{ Form::label('username', 'Username') }}<br/>
	{{ Form::text('username', Input::old('username')) }}
</p>

<!-- password field -->
<p>
	{{ Form::label('password', 'Password') }}<br/>
	{{ Form::password('password') }}
</p>

<!-- repeat password field -->
<p>
	{{ Form::label('password2', 'Repeat Password') }}<br/>
	{{ Form::password('password2') }}
</p>

<!-- Name -->
<p>
	{{ Form::label('name', 'Name') }}<br/>
	{{ Form::text('name') }}
</p>

<!-- email -->
<p>
	{{ Form::label('email', 'E-mail') }}<br/>
	{{ Form::text('email') }}
</p>


<!-- csrf token	 -->
{{ Form::token() }}

<!-- submit button -->
<p>{{ Form::submit('Register') }}</p>

{{ Form::close() }}
@endsection
