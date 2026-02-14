<!DOCTYPE html>
<html>
<head>
    <title>Extracted Text</title>
</head>
<body>

	<h2>Extracted Content</h2>

	<pre>{{ $text }}</pre>


	<div class="border-t pt-6">
		
					
		<form action="{{ route('invoice.confirm') }}" method="POST">
			@csrf
			<input type="hidden" name="stored_file" value="{{ $fileName }}">
			<button type="submit">Process Invoice Data from this</button>
		</form>
		
		
    </div>

</body>
</html>
