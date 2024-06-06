@extends('layout.main1')

@section('container')
    <section class="backdrop-brightness-75">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-[#e2ac8b] rounded-xl shadow light:border md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6">
                    <img src="/img/logo3.png" alt="" class="items-center justify-center w-40 mx-auto">
                    <form class="space-y-4 md:space-y-6" action="/register" method="post">
                        @csrf
                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Name :</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5 "
                                required="">
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Email :</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5 "
                                required="">
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Password :</label>
                            <input type="password" name="password" id="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5 "
                                required="">
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Confirm Password :</label>
                            <input type="password" name="c_password" id="c_password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5 "
                                required="">
                        </div>
                        <button type="submit"
                            class="w-full text-slate-950 bg-slate-50 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-slate-200">Registrasi</button>
                        <p class="text-sm text-black font-dark">
                            Sudah punya akun? <a href="/login"
                                class="font-medium text-slate-50 hover:underline hover:text-slate-50">Login disini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
