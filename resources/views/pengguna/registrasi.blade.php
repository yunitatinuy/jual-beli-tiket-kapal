@extends('layout.main1')

@section('container')
<section class="">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full bg-gradient-to-b from-[#E970C0] to-[#CA1A5A] rounded-lg shadow light:border md:mt-0 sm:max-w-md xl:p-0">
          <div class="p-6 space-y-4 md:space-y-6">
          <img src="/img/logo3.png" alt="" class="w-40 items-center justify-center mx-auto">
              <form class="space-y-4 md:space-y-6" action="/registrasi" method="post">
                @csrf
                  <div>
                      <label for="name" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Name:</label>
                      <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required="">
                  </div>
                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Email:</label>
                      <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required="">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Password:</label>
                      <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required="">
                  </div>
                  <button type="submit" class="w-full text-dark bg-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">Registrasi</button>
                  <p class="text-sm font-dark text-black">
                      Sudah punya akun? <a href="/login" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login disini</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>
@endsection