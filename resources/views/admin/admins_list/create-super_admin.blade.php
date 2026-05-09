@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">

            <div class="breadcrumb__content">

                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>Create Super Admin</h2>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">

            <div class="gallery__area bg-style">

                <div class="form-vertical__item bg-style">

                    <form method="POST"
                          action="{{ route('super_admin.store.super_admin') }}">

                        @csrf

                        {{-- Name --}}
                        <div class="input__group mb-25">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Full Name" required>
                        </div>

                        {{-- Email --}}
                        <div class="input__group mb-25">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>

                        {{-- Phone --}}
                        <div class="input__group mb-25">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="Phone">
                        </div>

                        {{-- Password --}}
                        <div class="input__group mb-25">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="input__group mb-25">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                        </div>

                        {{-- Submit --}}
                        <div class="input__button">
                            <button type="submit" class="btn btn-blue">
                                Create Super Admin
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection