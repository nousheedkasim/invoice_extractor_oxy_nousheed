(function() {
    const form = document.getElementById('uploadForm');
    const fileInput = document.getElementById('invoice_file');
    const btn = document.getElementById('submitBtn');
    const loader = document.getElementById('loader');
    const btnText = document.getElementById('btnText');
    const dropZone = document.getElementById('dropZone');

    if (!form) return;

    form.addEventListener('submit', (event) => {
        // Prevent submission if no file is selected
        if (fileInput.files.length === 0) {
            event.preventDefault();
            alert('Please select a file first.');
            return;
        }

        // Visual state changes
        btn.disabled = true;
        btnText.innerText = "Processing...";
        loader.classList.remove('hidden');
        
        // Disable the upload area so they don't change the file mid-upload
        if (dropZone) dropZone.classList.add('opacity-50', 'pointer-events-none');
    });
})();