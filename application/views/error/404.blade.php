@layout('layout')

<?php
$tweet_url = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
// var_dump($tweet_url); var_dump($_SERVER); exit;
$intent_url =	'http://twitter.com/intent/tweet?url=' . $tweet_url  .
				'&related=birrastoday&text=Hey, @julioelpoeta, you should check this page, it returns a 404';
$user_url = 'https://twitter.com/julioelpoeta';
$messages = array('Are you sure you wanted to come here?', "Oooops! This doesn't look like what you were looking for.", 'Doh! Something went wrong!');
?>

@section('content')
	<div id="notFound">
		<div class="overlay"></div>

		<div class="error-spacer"></div>
		<div role="main" class="main">
			<h1> {{ $messages[mt_rand(0, 2)];}} </h1>

			<h2>Error 404</h2>

			</br>

			<h3>What does this mean?</h3>

			<p>
				We couldn't find the page you requested on our servers. Probably you got here from
				a broken link or there's something wrong with the server.
			</p>

			<p>
				Perhaps you would like to go to our {{ HTML::link('/', 'home page'); }}? Or maybe
				you think this page should work, in that case <a href="{{ $intent_url }}" target="_blank">ping</a>
				<a href="{{ $user_url }}">@julioelpoeta</a> and let's see if he can fix it.
			</p>
		</div>
	</div>
@endsection
