<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>@yield('title')</title>
		<meta name="viewport" content="width=device-width">
		{{ Asset::styles() }}
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="home">Instapics</a>
					<div class="nav-collapse">
						<ul class="nav">
							@section('navigation')
							<li class="active"><a href="home">Home</a></li>
							@yield_section
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<div class="container">
			@yield('content')
		</div> <!-- /container -->
	{{ Asset::scripts() }}
	</body>
</html>
