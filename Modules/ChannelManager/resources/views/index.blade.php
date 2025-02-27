@extends('channelmanager::layouts.app')

@section('styles')
    <style>
        .section {
            position: relative;
            height: 100vh;
        }

        .section .section-center {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        #booking {
            font-family: 'Josefin Sans', sans-serif;
            background-image: url('../img/background.jpg');
            background-size: cover;
            background-position: center;
        }

        #booking::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .booking-form {
            background-color: #fff;
            max-width: 768px;
            width: 100%;
            margin: auto;
            padding: 30px;
            border-radius: 3px;
        }

        .booking-form .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .booking-form .form-header h1 {
            font-family: 'Cardo', serif;
            font-weight: 700;
            text-transform: capitalize;
            font-size: 38px;
            color: #0c0c09;
            margin-top: 0;
            margin-bottom: 0px;
        }

        .booking-form .form-group {
            position: relative;
            margin-bottom: 30px;
        }

        .booking-form .form-control {
            background-color: #fff;
            height: 50px;
            padding: 0px 20px;
            border: 1px solid #e1e1e1;
            -webkit-box-shadow: none;
            box-shadow: none;
            border-radius: 3px;
            color: #6b6b6d;
            font-size: 14px;
        }

        .booking-form .form-control::-webkit-input-placeholder {
            color: #e1e1e1;
        }

        .booking-form .form-control:-ms-input-placeholder {
            color: #e1e1e1;
        }

        .booking-form .form-control::placeholder {
            color: #e1e1e1;
        }

        .booking-form .form-control:focus {
            border-color: #c99552;
        }

        .booking-form input[type="date"].form-control:invalid {
            color: #e1e1e1;
        }

        .booking-form select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .booking-form select.form-control+.select-arrow {
            position: absolute;
            right: 0px;
            bottom: 0px;
            width: 24px;
            text-align: center;
            pointer-events: none;
            background: #e1e1e1;
            height: 50px;
            line-height: 50px;
            color: rgba(107, 107, 109, 0.3);
            font-size: 14px;
            border-radius: 0px 3px 3px 0px;
            -webkit-transition: 0.2s all;
            transition: 0.2s all;
        }

        .booking-form select.form-control+.select-arrow::after {
            content: '\279C';
            display: block;
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .booking-form select.form-control:focus+.select-arrow {
            background: #c99552;
            color: #fff;
        }

        .booking-form .form-label {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 3px;
            display: inline-block;
            color: #c99552;
            text-transform: capitalize;
        }

        .booking-form .in-out {
            position: absolute;
            font-size: 24px;
            color: #c99552;
            right: -15px;
            width: 30px;
            height: 30px;
            line-height: 30px;
            z-index: 20;
            text-align: center;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            bottom: 25px;
        }

        .booking-form .form-btn {
            margin-top: 23px;
        }

        .booking-form .submit-btn {
            color: #fff;
            background-color: #c99552;
            font-weight: 700;
            height: 50px;
            border: none;
            display: block;
            font-size: 14px;
            width: 100%;
            text-transform: capitalize;
            border-radius: 3px;
        }
    </style>
@endsection

@section('content')

<div class="border rounded booking-form">
    
    <form id="checkAvailabilityForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <span class="form-label">Check In</span>
                    <input class="form-control" type="date" id="checkIn" name="check_in" required>
                </div>
                <span class="in-out hidden-xs hidden-sm">&#8652;</span>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <span class="form-label">Check out</span>
                    <input class="form-control" type="date" id="checkOut" name="check_out" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <span class="form-label">Room Type</span>
                    <select class="form-control" name="room_type" id="roomType">
                        <option value=""></option>
                        @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    {{-- <span class="select-arrow"></span> --}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <span class="form-label">Adults</span>
                    <input type="number" id="people" class="form-control" name="people" min="1" required>
                    <span class="select-arrow"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <span class="form-label">Children</span>
                    <select class="form-control">
                        <option>0</option>
                        <option>1</option>
                        <option>2</option>
                    </select>
                    <span class="select-arrow"></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-btn">
                    <button type="submit" class="submit-btn">Check availability</button>
                </div>
            </div>
        </div>
    </form>
    <div class="container text-center">
        <span class="text-black caveat" style="font-size: 25px;">Powered by <strong><a class="text-black" href="https://ndako.koverae.com">Ndako</a></strong></span>
    </div>
</div>
@endsection
