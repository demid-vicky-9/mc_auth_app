@extends('layouts.app')

@section('title', 'Login page')

@section('content')

    <main id="success-page">
        <section class="page-form">
            <div class="page-form__container">
                <div class="page-form__wrapper">
                    <form action="{{ route('login.user') }}" method="post" class="page-form__info form">
                        @csrf
                        <h3 class="form__title">Login</h3>
                        @if(session('error'))
                            <p class="fw-bold text-danger">{{ session('error') }}</p>
                        @endif
                        <div class="form__wrap">
                            <div class="form__item">
                                <label>
                                    <input data-error="" data-required="phone" data-validate
                                           name="phone" type="tel" placeholder="Enter phone number"
                                           class="form__input phone-mask">
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="form__button btn">Enter</button>
                        <a href="{{ route('auth.register') }}" class="form__link">New user? Register</a>
                    </form>
                </div>
            </div>
        </section>
    </main>

@endsection
