<div class="navbar navbar-default my-navbar" role="navigation">
	<div id="navbar-container">
		<div class="navbar-header">
<!-- 			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> -->
<!-- 				<span class="sr-only">Toggle navigation</span> -->
<!-- 				<span class="icon-bar"></span> -->
<!-- 				<span class="icon-bar"></span> -->
<!-- 				<span class="icon-bar"></span> -->
<!-- 			</button> -->
			<a class="navbar-brand my-navbar-brand" href="/"><img id="logo" src="/img/birrastoday.png"><span class="header">BIRRAS.TODAY</span></a>
		</div>
		<div class="navbar navbar-right">
				<ul class="nav navbar-nav ">
<!-- 					<li class="active"><a href="#">Link</a></li> -->
					<li><a id="hall-of-fame" class="navbar-link" href="#">Hall of fame</a></li>
					@if(Auth::guest())
						<div class="form-group navbar-form navbar-right home-login">
							<a href="/auth/login"><button type="submit" class="btn my-btn login">Sign in</button></a>
						</div>
					@else
						<div class="form-group navbar-form navbar-right home-login">
							<a href="/auth/logout"><button type="submit" class="btn my-btn logout">Sign out</button></a>
						</div>
					@endif
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
				</ul>
		</div><!--/.navbar-collapse -->
	</div> <!-- end container -->
</div><!-- end navbar -->
