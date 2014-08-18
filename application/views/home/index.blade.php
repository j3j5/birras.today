@layout('layout')

@section('content')
	<section id="bar-wall">
		@if(!empty($top_users))
			<div class="chalkboard chalkboard-left">
				Hall of Fame
				<ul>
					@foreach($top_users as $user)
						<li></li>
					@endforeach
				</ul>
			</div>
		@endif
		<div id="action" class="no-box-sizing">
			<div id="keg">
				<div id="pipe-handle"></div>
				<div id="pipe"></div>
				@if($logo)
					<div id="grolsch" class="beer-brand-logo-keg grolsch">
						@include('objects.grolsch')
					</div>
				@else
					<div id="estrella" class="beer-brand-logo-keg">
						@include('objects.estrella')
					</div>
				@endif
				<div id="pipe-front"></div>
			</div>

			<div class="glass">
				<div class="beer"></div>
				<div class="handle">
					<div class="top-right"></div>
					<div class="bottom-right"></div>
				</div>
				<div class="front-glass">
				@if(!$logo)
					<div class="beer-brand-logo-glass grolsch">
						@include('objects.grolsch')
					</div>
				@else
					<div class="beer-brand-logo-glass estrella">
						@include('objects.estrella')
					</div>
				@endif
				</div>
			</div>
		</div>
		@if(!empty($top_places))
			<div class="chalkboard chalkboard-right">
				<h3>Hall of Fame</h3>
				<h4>(Places)</h4>
				<ul>
					@foreach($top_places as $place)
						<li title="{{ $place['count']['count'] }} times">{{ $place['place']->name }} →
							@if(!empty($place['count']['crossed']))
								<?php $crossed = (int)($place['count']['count'] / 5); ?>
								@for($i=0; $i<$crossed; $i++)
									<s>{{ $place['count']['crossed'] }}</s>
								@endfor
							@endif
							{{ $place['count']['left'] }}
						</li>
					@endforeach
				</ul>
			</div>
		@endif
	</section>
		<h1 id="title1">
			BIRRAS.TODAY
		</h1>
	@if ( !empty($appointments) )
		<h2>YES,</h2>
		@foreach($appointments AS $app)
			<h3>
				TODAY, <strong>{{ date('Y-m-d', $app['a_date_ts']); }}</strong> around {{ date('H:i', $app['a_date_ts']); }}
			@if(!empty($app['appointment_name']))
				@if(!empty($app['link']))
					<a href="{{ $app['link'] }}">
						<strong>{{ $app['appointment_name'] }}</strong>
					</a>
				@else
					{{ $app['appointment_name'] }}
				@endif
				at
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
			@if($app['public'])
			<span>
				<a href="https://twitter.com/{{ $app['added_by'] }}/status/{{ $app['tweet_id'] }}" target="_blank">
					Added
				</a> by
				<a href="https://twitter.com/{{ $app['added_by'] }}" target="_blank">
					@{{ $app['added_by'] }}
				</a>
			</span>
			@endif
		@endforeach
	@else
		<h2>Not known yet</h2>
		<div id="instructions">Go to Skype and give your opinion or
			<a href="https://twitter.com/intent/tweet?text={{ urlencode("@birrastoday #comingto NAME_OF_THE_PLACE #time TIME #map LINK_TO_THE_LOCATION "); }}" target="_blank">
				send a tweet
			</a> to <a href="https://twitter.com/birrastoday" target="_blank">the bot</a>!!</div>
	@endif
@endsection
