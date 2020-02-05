<html lang="id">
    <head>
        <title>Aquila Land - @yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        
        @include('layouts/head')
        @yield('headscript')
    </head>

    <body>
        <div class="wrapper">
            @include('layouts/header')

            @include('layouts/sidebar')

            <div class="main-panel">
			<div class="content">
				<div class="container-fluid">
					@yield('content')
				</div>
			</div>
			
		</div>
		


        </div>

    @include('layouts/footer')

    </body>
</html>