@extends('layout.main')

@section('container')
<div class="w-[544px] h-[630px] center border rounded-[50px] my-28 mx-auto bg-gradient-to-tr from-sky-700 to-slate-100">
<img src="img/logo2.png" alt="" class="w-32 mx-auto mt-14">
    <div class="px-3 py-14">
        <form action="/login/index" method="POST">
            @csrf 
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" value="{{ Session::get('email') }}" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <form>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember Me:</label>
            <br>
            </form>
            <div class="mb-3 d-grid">
                <button name="submit" type="submit" class="btn hover:bg-slate-300 w-20 mx-auto">Login</button>
            </div>
        </form>
    </div> 
</div>
@endsection