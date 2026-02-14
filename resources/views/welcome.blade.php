<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Extractor</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

<script src="{{ asset('js/upload-handler.js') }}" defer></script>
</head>
<body class="app-container">

    <div class="card">
        <header>
            <h1 class="title">Welcome Invoice Extractor</h1>
            <p class="subtitle">Upload your invoice file to extract data instantly.</p>
        </header>

        @if (isset($errors) && $errors->any())
            <div class="alert alert-error">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="uploadForm" action="{{ route('invoice.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf
			<div id="dropZone" class="drop-zone">
                <input 
                    type="file" 
                    name="invoice_file" 
                    id="invoice_file" 
                    class="hidden-input" 
                    accept=".pdf, image/*"
                >

                <label for="invoice_file" class="drop-zone-content">
                    <span class="drop-zone-main-text">Click to Upload File</span>
                    <p class="drop-zone-sub-text">PDF, JPG, PNG supported</p>
                </label>
            </div>

            <button type="submit" id="submitBtn" class="btn-primary">
                <span id="btnText">Upload Invoice</span>
                
                <div id="loader" class="loader hidden"></div>
            </button>
        </form>
    </div>

</body>
</html>