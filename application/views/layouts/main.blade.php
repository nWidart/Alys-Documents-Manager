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
		<div class="navbar navbar-inverse">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#" name="top">Alys Document Manager</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a href="{{ URL::to_route('homepage');}}"><i class="icon-home icon-white"></i> Home</a></li>
							<li class="divider-vertical"></li>
							<li><a href="{{ URL::to_route('docIndex'); }}"><i class="icon-home icon-white"></i> Library</a></li>
					</div>
					<!--/.nav-collapse -->
				</div>
				<!--/.container-fluid -->
			</div>
			<!--/.navbar-inner -->
		</div>
		<!--/.navbar -->
		<div class="container">
			@yield('content')
		</div> <!-- /container -->
	{{ Asset::scripts() }}
	<script type="text/javascript">
		function alertSuccess( text )
		{
			alertify.success( text );
		}
		function alertError ( text )
		{
			alertify.error( text );
		}
		function clear_form_elements(ele) {
			 $(ele).find(':input').each(function() {
				  switch(this.type) {
						case 'password':
						case 'select-multiple':
						case 'select-one':
						case 'text':
						case 'textarea':
							 $(this).val('');
							 break;
						case 'checkbox':
						case 'radio':
							 this.checked = false;
				  }
			 });
		}
	</script>

	<!-- Extra page specific JS Scripts -->
	@yield('scripts')
	</body>
</html>
