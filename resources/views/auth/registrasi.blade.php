@extends('layout.main1')

@section('container')
<section class="backdrop-brightness-75">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full bg-[#e2ac8b] rounded-xl my-10 shadow light:border sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4">
                <img src="/img/logo3.png" alt="" class="items-center justify-center w-40 mx-auto">

                <!-- Display validation errors -->

                <form class="space-y-4" action="/register" method="post" onsubmit="return validateForm()">
                    @csrf
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Name :</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5" >
                        @error('name')
                        <p class="text-red-600 mt-1 text-xs font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Email :</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5" >
                        @error('email')
                        <p class="text-red-600 mt-1 text-xs font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Password :</label>
                        <input type="password" name="password" id="password" value="{{ old('password') }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5" >
                        @error('password')
                        <p class="text-red-600 mt-1 text-xs font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="c_password" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Confirm Password :</label>
                        <input type="password" name="c_password" id="c_password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5" >
                        @error('c_password')
                        <p class="text-red-600 mt-1 text-xs font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-4">
                    <button type="submit" class="w-full text-slate-950 bg-[#f49855] focus:ring-2 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-[#ED9455] hover:text-slate-100">Registrasi</button>
                    <p class="text-sm mt-2 text-black font-dark">
                        Sudah punya akun? <a href="/login" class="font-medium text-slate-50 hover:underline hover:text-slate-50">Login disini</a>
                    </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    function validateForm() {
        var password = document.getElementById("password").value;
        var c_password = document.getElementById("c_password").value;
        if (password !== c_password) {
            showAlert("Password and Confirm Password do not match.", "danger");
            return false;
        }
        return true;
    }

    function showAlert(message, type) {
        var alertDiv = document.createElement("div");
        alertDiv.classList.add("alert", "alert-" + type);
        alertDiv.setAttribute("role", "alert");
        alertDiv.innerHTML = message;
        var container = document.querySelector(".container");
        container.insertBefore(alertDiv, container.firstChild);
        setTimeout(function() {
            alertDiv.remove();
        }, 5000);
    }
</script>
@endsection