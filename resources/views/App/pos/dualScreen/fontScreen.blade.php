<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Screen 1</title>
    <link rel="stylesheet" href="{{asset('assets/css/loader.style.css')}}">
</head>
<body>
    <div id="loading-page">
        <div class="loader">

        </div>
        <div class="loadingLogo">
            <img src="{{asset('default/pico.png')}}"  alt="" width="50px">
        </div>
    </div>
    <h1>hello</h1>
</body>
<script>

    window.addEventListener('load', function () {
            window.setTimeout(() => {
                // Hide the loading page when the website is loaded
            var loadingPage = document.getElementById('loading-page');
            loadingPage.style.display = 'none';
            }, 1000);
        });
</script>
</html>
