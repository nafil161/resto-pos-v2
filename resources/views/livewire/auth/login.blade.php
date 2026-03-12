<div>
    <div class="authentication-inner row m-0">
        <div class="d-none d-xl-flex col-xl-8 p-0">
            <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}" alt="auth-cover"
                    class="my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png">
                <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}" alt="auth-cover-bg"
                    class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png">
            </div>
        </div>
        <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
            <div class="w-px-400 mx-auto mt-12 pt-5">
                <!-- Title -->
                <h3 class="mb-1">Welcome to {{ config('app.name') }}! 👋</h3>
                <p class="mb-6">Please sign in to your account and start the adventure</p>

                <form wire:submit.prevent="login">

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            wire:model="email" placeholder="Enter your email" autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label">Password</label>
                            <a href="#" class="small">Forgot Password?</a>
                        </div>
                        <div class="input-group input-group-merge has-validation">
                            <input type="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                wire:model="password" placeholder="············" aria-describedby="password">
                            <span class="input-group-text cursor-pointer"><i
                                    class="icon-base ti tabler-eye-off"></i></span>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="mb-8">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary d-grid w-100" wire:loading.attr="disabled">
                        <span wire:loading wire:target="login" class="spinner-border spinner-border-sm me-1"
                            role="status" aria-hidden="true"></span>
                        Sign in
                    </button>

                </form>

                <p class="text-center mt-6">
                    <span>New on our platform?</span>
                    <a href="{{ route('register') }}" wire:navigate>
                        <span>Create an account</span>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>