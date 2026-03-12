<div>
    <div class="authentication-inner row m-0">
        <div class="d-none d-xl-flex col-xl-8 p-0">
            <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/img/illustrations/auth-register-illustration-light.png') }}" alt="auth-cover"
                    class="my-5 auth-illustration"
                    data-app-light-img="illustrations/auth-register-illustration-light.png"
                    data-app-dark-img="illustrations/auth-register-illustration-dark.png">
                <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}" alt="auth-cover-bg"
                    class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png">
            </div>
        </div>
        <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
            <div class="w-px-400 mx-auto mt-12 pt-5">
                <h4 class="mb-1">Adventure starts here 🚀</h4>
                <p class="mb-6">Make your app management easy and fun!</p>

                <form wire:submit.prevent="register">
                    <div class="mb-6">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" wire:model="username" placeholder="Enter your username" autofocus>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" wire:model="email" placeholder="Enter your email">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-6 form-password-toggle">
                        <label class="form-label" for="password">Password</label>
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
                    <div class="mb-6 mt-8">
                        <div class="form-check mb-8 ms-2">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                                id="terms-conditions" wire:model="terms">
                            <label class="form-check-label" for="terms-conditions">
                                I agree to
                                <a href="#">privacy policy & terms</a>
                            </label>
                            @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary d-grid w-100 waves-effect waves-light" type="submit"
                        wire:loading.attr="disabled">
                        <span wire:loading wire:target="register" class="spinner-border spinner-border-sm me-1"
                            role="status" aria-hidden="true"></span>
                        Sign up
                    </button>
                </form>

                <p class="text-center mt-6">
                    <span>Already have an account?</span>
                    <a href="{{ route('login') }}" wire:navigate>
                        <span>Sign in instead</span>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>