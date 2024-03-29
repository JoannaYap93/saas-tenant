<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title> @yield('title') | {{config('app.name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" /> --}}
        <meta content="Webby" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ global_asset('images/华盛logo-white.svg') }}">
        @include('layouts.head')
        @yield('css')
  </head>

    @yield('body')
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    @yield('content')

    @include('layouts.footer-script')
    </body>
</html>