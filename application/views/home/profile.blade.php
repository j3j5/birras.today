@layout('layout')

@section('content')
	<div class="content">
		<h2>Welcome "{{ Auth::user()->username }}" to the protected page!</h2>
		<p>Your user ID is: {{ Auth::user()->id }}</p>
		<p>Your email: {{ Auth::user()->email }}</p>
	</div>
@endsection
