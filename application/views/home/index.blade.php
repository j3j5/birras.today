<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Birras TODAY...WHERE?</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="icon" type="image/gif" href="/favicon.ico" />

		{{ Asset::styles(); }}

		{{ Asset::scripts(); }}
		<noscript><p><img src="http://dezwartepoet.nl/piwik/piwik.php?idsite=2" style="border:0;" alt="" /></p></noscript>

	</head>
	<body>
 		<script type="text/javascript" src="http://leaverou.github.io/prefixfree/prefixfree.min.js"></script>

		<section>
			<div id="action">
				<div id="keg">
					<div id="pipe-handle"></div>
					<div id="pipe"></div>
					<div id="pipe-front"></div>
				</div>

				<div class="glass">
					<div class="beer"></div>
					<div class="handle">
						<div class="top-right"></div>
						<div class="bottom-right"></div>
					</div>
					<div class="front-glass"></div>
				</div>
			</div>
		</section>

		<h1>
			BIRRAS.TODAY
		</h1>
	@if ( !empty($appointments) )
		<h2>YES,</h2>
		@foreach($appointments AS $app)
			<h3>
				TODAY, <strong>{{ date('Y-m-d'); }}</strong> around {{ date('H:i', $app['a_date_ts']); }},
			@if(!empty($app['appointment_name']))
				@if(!empty($app['link']))
					<a href="{{ $app['link'] }}">
						<strong>{{ $app['appointment_name'] }}</strong>
					</a>
				@else
					{{ $app['appointment_name'] }}
				@endif
			@endif
			</h3>

			@if(!empty($app['website']))
				<a href="{{$app['website']}}" >
					<h3>{{ $app['place_name'] }}</h3>
				</a>
			@else
				<h3>{{ $app['place_name'] }}</h3>
			@endif

			@if(!empty($app['address']))
				<p>Address is:
					@if(!empty($app['map_link']))
						<a href="{{ $app['map_link'] }}">
							{{$app['address']}}
						</a>
					@else
						{{ $app['address'] }}
					@endif
				</p>
			@endif
			<span>
				<a href="https://twitter.com/{{ $app['added_by'] }}/status/{{ $app['tweet_id'] }}" target="_blank">
					Added
				</a> by
				<a href="https://twitter.com/{{ $app['added_by'] }}" target="_blank">
					@{{ $app['added_by'] }}
				</a>
			</span>
		@endforeach
	@else
		<h2>NOT KNOWN YET</h2>
		<h3>GO TO SKYPE AND GIVE YOUR OPINION OR
			<a href="https://twitter.com/intent/tweet?text={{ urlencode("@birrastoday #comingto NAME_OF_THE_PLACE #time TIME #map LINK_TO_THE_LOCATION "); }}" target="_blank">
				SEND A TWEET
			</a> TO <a href="https://twitter.com/birrastoday" target="_blank">THE BOT!!</a></h3>
	@endif
	</body>
</html>

