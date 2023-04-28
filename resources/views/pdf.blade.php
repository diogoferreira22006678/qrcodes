@extends('_layouts.layout', [
    'title' => 'pdfReader',
    'simple' => true
])

@section('head')
<link rel="stylesheet" href="/assets/css/user.css" />
@endsection

@section('body')

<div id="pdf-container">
  <div id="pdf-container-inner"></div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js" integrity="sha512-E8QSvWZ0eCLGk4km3hxSsNmGWbLtSCSUcewDQPQWZF6pEU8GlT8a5fF32wOl1i8ftdMhssTrF/OhyGWwonTcXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/aes.min.js" integrity="sha512-4b1zfeOuJCy8B/suCMGNcEkMcQkQ+/jQ6HlJIaYVGvg2ZydPvdp7GY0CuRVbNpSxNVFqwTAmls2ftKSkDI9vtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

(async function(){
  let pdfjsLib = window['pdfjs-dist/build/pdf'];
  let inner = document.getElementById('pdf-container-inner');
  
  let key = hex2bin("{{ config('pdf.aes_key') }}");
  key = await crypto.subtle.importKey('raw', key, 'AES-CBC', false, ['decrypt']);
  let iv = hex2bin("{{ config('pdf.aes_iv') }}");
  let url = "{{ $path }}";

  let xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.responseType = 'arraybuffer';
  xhr.onload = async function(e) {
    if (this.status == 200) {
      // get binary data as a response
      let data = this.response;
      let dec = await crypto.subtle.decrypt(
        {name: 'AES-CBC', iv: iv},
        key,
        data
      );

      let blob = new Blob([dec], {type: 'application/pdf'});
      let object_url = URL.createObjectURL(blob);
      console.log(object_url);

      // The workerSrc property shall be specified.
      pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

      // Asynchronous download of PDF
      var loadingTask = pdfjsLib.getDocument(object_url);
      loadingTask.promise.then(function(pdf) {
        console.log('PDF loaded');
        
        // Fetch the all the pages
        var max = pdf._pdfInfo.numPages;
        for(let pageNumber = 1; pageNumber <= max; pageNumber++){
          let canvas = document.createElement('canvas');
          inner.appendChild(canvas);
          pdf.getPage(pageNumber).then(function(page) {
            console.log('Page loaded');
            
            var scale = 1.5;
            var viewport = page.getViewport({scale: scale});

            // Prepare canvas using PDF page dimensions
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            var renderContext = {
              canvasContext: context,
              viewport: viewport
            };
            var renderTask = page.render(renderContext);
            renderTask.promise.then(function () {
              console.log('Page rendered');
            });
          });
        }


      }, function (reason) {
        // PDF loading error
        console.error(reason);
      });
    }
  };
  xhr.send();


  function hex2bin(str) {
    var bytes = [];

    for(var i=0; i<str.length; i+=2)
        bytes.push(parseInt(str.substr(i, 2), 16));

    return new Uint8Array(bytes).buffer;
  }

  // Loaded via <script> tag, create shortcut to access PDF.js exports.


})();

document.currentScript.remove();
</script>

@endsection