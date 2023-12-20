@extends('layouts.app')

@section('title', 'Registration page')

@section('content')

    <main id="success-page">
        <section class="page-form">
            <div class="page-form__container">
                <div class="page-form__wrapper">
                    <form action="{{ route('auth.register.create') }}" method="post" class="page-form__info form">
                        @csrf
                        <h3 class="form__title">Register now</h3>
                        @if(session('error'))
                            <p class="fw-bold text-danger">{{ session('error') }}</p>
                        @endif
                        <div class="form__wrap">
                            <div class="form__item">
                                <label>
                                    <input data-error="" data-required data-validate name="name" type="text"
                                           placeholder="Name" class="form__input">
                                </label>
                            </div>
                            <div class="form__item">
                                <label>
                                    <input data-error="" data-required="phone" data-validate
                                           name="phone" type="tel" placeholder="Phone number"
                                           class="form__input phone-mask">
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="form__button btn">Register</button>
                        <a href="{{ route('social.auth', 'google') }}" class="form__button btn mt-2">
                            Register with Google
                        </a>
                        <a href="{{ route('login') }}" class="form__link">Login</a>
                    </form>
                </div>
            </div>
        </section>
    </main>

@endsection
