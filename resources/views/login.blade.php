@extends('_layouts.layout', [
    'title' => 'Login',
    'simple' => true
])

@section('head')

<link rel="stylesheet" href="/assets/css/login.css">

@endsection

<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-dark text-white" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
  
              <div class="mb-md-5 mt-md-4 pb-5">
  
                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                <p class="text-white-50 mb-5">Please enter your username and password!</p>
                <form class="form" action="{{ route('login') }}" method="POST">
                         @csrf
                        <div class="form-outline form-white mb-4">
                        <input type="user_name" placeholder="Username" id="user_name" name="user_name" class="form-control form-control-lg" />
                        </div>
        
                        <div class="form-outline form-white mb-4">
                        <input type="password" placeholder="Password" id="user_pass" name="user_pass" class="form-control form-control-lg" />
                        </div>
                
                        <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                </form>
              </div>  
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>