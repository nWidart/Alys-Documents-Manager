@layout('layouts.main')

@section('title')
Document Manager | Lemmens.com
@endsection

@section('content')
	<div class="row-fluid">
		<a href="{{ URL::to_route('new_doc') }}" class="btn btn-large btn-info">Manage your library</a>
	</div>

@endsection
