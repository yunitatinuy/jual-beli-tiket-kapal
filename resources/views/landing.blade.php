<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marko+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Marko+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Landing Page</title>

</head>
<body class="bg-gradient-to-tr from-sky-700 to-slate-100">

<!-- NAV BAR -->
<nav class="bg-gradient-to-br from-sky-800 to-slate-100 shadow-xl">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/img/logo.png" alt="logo" width="50" class="float-left flex flex-col object-cover">
            <span class="self-center text-3xl font-marko whitespace-nowrap uppercase">Anang Ferry</span>
        </a>

        <!-- dropdown -->
        <div class="sidebar">
            <ul class="flex flex-row font-marko">
                <li><a href="#"class="px-2">Home</a></li>
                <li><a href="#"class="px-2">Contact</a></li>
                <li><a href="#"class="px-2">Login</a></li>
            </ul>
        </div>

    </div>
</nav>



    <div class="w-screen h-screen justify-center items-center">
  <div class="m-10 flex justify-center">
    <span class="text-3xl font-marko uppercase">Anang Ferry</span>
</div>
    <div>
    <table class="flex justify-center font-jsMath font-bold">
  <thead>
    <tr>
      <td rowspan="10"><img src="/img/logo.png" alt="logo" width="110"></td>
      <td>Wujudkan</td>
    </tr>
    
    <tr>
        <td>Perjalanan</td>
    </tr>
    <tr>
        <td>Anda</td>
    </tr>
    <tr>
        <td>Bersama</td>
    </tr>
    <tr>
        <td>Kami</td>
    </tr>
  </tbody>
</table>
<br>
</div>

     <div class="flex justify-center">
        <a href="/dashboard_pengguna" class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-sky-300 rounded-lg hover:bg-sky-300 hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300 ">
        Cari Tiket
        <img src="/img/cari.png" alt="" class="w-4 ms-2">
         </a>
     </div>
</div>
</div>
</body>
</html>