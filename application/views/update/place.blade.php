@layout('layout')

@section('content')

	<h1>Add a new place</h1>
	<div id="form" style="margin: 60px;">
		{{ Form::open('update/place', 'POST', array('class' => 'form-horizontal')) }}
			<fieldset>
				<div class="form-group">
					<!-- Name -->
					{{ Form::label('place_name', 'Name', array('class' => 'control-label col-xs-2', 'for' => 'place_name')) }}
					<div class="col-xs-10">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
							{{ Form::text('place_name', Input::old('place_name'), array('class' => 'form-control')) }}
						</div>
						<p class="help-block">Add the name of the bar or place, you can add more aliases later.</p>
					</div>
				</div>
				<div class="form-group">
					<!-- Description -->
					{{ Form::label('description', 'Description', array('class' => 'control-label col-xs-2', 'for' => 'description')) }}
					<div class="col-xs-10">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
							{{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
						</div>
						<p class="help-block">Add a description for it.</p>
					</div>
				</div>
				<div class="form-group">
					<!-- Avatar -->
					{{ Form::label('avatar', 'Avatar', array('class' => 'control-label col-xs-2', 'for' => 'avatar')) }}
					<div class="col-xs-10">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
							{{ Form::text('avatar', Input::old('avatar'), array('class' => 'form-control')) }}
						</div>
						<p class="help-block">Add the URL of an image for the bar.</p>
					</div>
				</div>
				<div class="form-group">
					<!-- Address -->
					{{ Form::label('address', 'Address', array('class' => 'control-label col-xs-2', 'for' => 'address')) }}
					<div class="col-xs-10">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
							{{ Form::text('address', Input::old('address'), array('class' => 'form-control')) }}
						</div>
						<p class="help-block">Add the address of the place.</p>
					</div>
				</div>
				<div class="form-group">
					<!-- website -->
					{{ Form::label('website', 'Place URL', array('class' => 'control-label col-xs-2', 'for' => 'website')) }}
					<div class="col-xs-10">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-bookmark"></span></span>
							{{ Form::text('website', Input::old('website'), array('class' => 'form-control')) }}
						</div>
						<p class="help-block">Does the bar have a website? Add it here!</p>
					</div>
				</div>

				<!-- map_link -->
				<div class="form-group">
					{{ Form::label('map_link', 'URL to a map', array('class' => 'control-label col-xs-2', 'for' => 'map_link')) }}
					<div class="col-xs-10">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
							{{ Form::text('map_link', Input::old('map_link'), array('class' => 'form-control')) }}
						</div>
						<p class="help-block">Add the link of a map, pointing to the place in case the address is too hard to find.</p>
					</div>
				</div>

				<div class="form-group">
					<!-- has terrace -->
					<div class="col-xs-offset-2 col-xs-10">
						{{ Form::label('has_terrace', 'Does it have a terrace?', array('class' => 'control-label col-xs-2', 'for' => 'has_terrace')) }}
						<div class="checkbox">
							<label>
								{{  Form::checkbox('has_terrace', '0') }}
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-offset-2 col-xs-10">
						{{ Form::button('Add alias', array('id' => 'addAlias', 'class' => "btn btn-sm btn-default")) }}
					</div>
				</div>

				<!-- csrf token	 -->
				{{ Form::token() }}

				<div class="form-group">
					<!-- submit button -->
					<div class="col-xs-offset-2 col-xs-10">
						{{ Form::submit('Add/update', array('class' => 'btn btn-sm btn-primary')) }}
					</div>
				</div>
			</fieldset>
		{{ Form::close() }}
	</div>

@endsection
