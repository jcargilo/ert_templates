<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="height: 100%">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'File Manager') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
    <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">
</head>
<body style="height: 100%">
    <div class="container-fluid" style="height: 100%">
        <div class="row" style="height: 100%">
            <div class="col-md-12" id="fm-main-block" style="height: 100%">
                <div id="fm" style="height: 100%"></div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
                let urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('id')) {
                    window.parent.fmSetLink(urlParams.get('id'), fileUrl);
                }
            });
        });
    </script>
</body>
</html>

