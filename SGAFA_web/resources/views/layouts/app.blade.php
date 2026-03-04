<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>S.G.A.F.A QR - @yield('title')</title>

   <link rel="preconnect" href="https://fonts.bunny.net">
   <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

   @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-sgafa-background text-sgafa-text-main antialiased selection:bg-sgafa-accent selection:text-white">

   <main class="min-h-screen flex flex-col">
      @yield('content')
   </main>

</body>

</html>