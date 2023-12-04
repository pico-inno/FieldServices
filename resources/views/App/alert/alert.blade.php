<script src={{asset('customJs/toastrAlert/alert.js')}}></script>
@if(session('success'))

    <script>
        success("{!! session('success') !!}");
    </script>
@endif
@if(session('warning'))

    <script>
        warning("{!! session('warning') !!}");
    </script>
@endif
@if(session('error'))

    <script>
        error("{!! session('error') !!}");
    </script>
@endif

@if(session('noti'))
    <script>
        noti("{!! session('noti') !!}");
    </script>

@endif
