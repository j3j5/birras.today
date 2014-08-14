<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div id="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">BIRRAS.TODAY</a>
		</div>
		<div class="navbar-collapse collapse">
<!-- 				<ul class="nav navbar-nav"> -->
<!-- 					<li class="active"><a href="#">Link</a></li> -->
<!-- 					<li><a href="#">Link</a></li> -->
<!-- 					<li><a href="#">Link</a></li> -->
<!-- 					<li class="dropdown"> -->
<!-- 						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a> -->
<!-- 						<ul class="dropdown-menu" role="menu"> -->
<!-- 							<li><a href="#">Action</a></li> -->
<!-- 							<li><a href="#">Another action</a></li> -->
<!-- 							<li><a href="#">Something else here</a></li> -->
<!-- 							<li class="divider"></li> -->
<!-- 							<li class="dropdown-header">Nav header</li> -->
<!-- 							<li><a href="#">Separated link</a></li> -->
<!-- 							<li><a href="#">One more separated link</a></li> -->
<!-- 						</ul> -->
<!-- 					</li> -->
<!-- 				</ul> -->
			@if(Auth::guest())
				{{ Form::open('auth/login', 'POST', array('id' => 'loginForm', 'class' => "navbar-form navbar-right", "role" => "form")) }}

					<div class="form-group">
						{{ Form::text('username', Input::old('username'), array('class' => 'form-control')) }}

					</div>

					<div class="form-group">
						{{ Form::password('password', array('class' => 'form-control')) }}
<!-- 					<input type="password" placeholder="Password" class="form-control"> -->
					</div>
					<!-- csrf token	 -->
					{{ Form::token() }}

					<button type="submit" class="btn btn-sm btn-success">Sign in</button>
				{{ Form::close() }}
			@else
				<div class="form-group navbar-right">
					<a href="/auth/logout"><button type="submit" class="btn btn-sm btn-warning">Sign out</button></a>
				</div>
			@endif
		</div><!--/.navbar-collapse -->
	</div> <!-- end container -->
</div><!-- end navbar -->
