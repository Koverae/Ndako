@extends('channelmanager::layouts.app')

@section('styles')
    <style>
    .booking_form {
        background-color: #fff;
        padding: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        border-radius: 5px;
        margin: 15px auto 0;
        position: relative;
        -webkit-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.3);
        -moz-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.3);
      }
      @media (max-width: 991px) {
        .booking_form {
          padding: 0;
          background: none;
          -webkit-box-shadow: none;
          -moz-box-shadow: none;
          box-shadow: none;
        }
      }
      .booking_form input {
        border: 0;
        height: 50px;
        padding-left: 15px;
        border-radius: 0;
        background-color: transparent;
        border-right: 1px solid #d9e1e6;
        font-weight: 500;
        font-size: 15px;
        font-size: 0.9375rem;
        color: #6c757d;
      }
      @media (max-width: 991px) {
        .booking_form input {
          border: none;
          background-color: #fff;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
          -ms-border-radius: 5px;
          border-radius: 5px;
          margin-bottom: 10px;
        }
      }
      .booking_form input:focus {
        box-shadow: none;
        border-right: 1px solid #d9e1e6;
      }
      @media (max-width: 991px) {
        .booking_form input:focus {
          border-right: none;
        }
      }
      .booking_form .form-group {
        margin: 0;
        position: relative;
      }
      @media (max-width: 991px) {
        .booking_form .form-group {
          margin-bottom: 5px;
        }
      }
      .booking_form .form-group i {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
        font-size: 21px;
        font-size: 1.3125rem;
        position: absolute;
        line-height: 50px;
        top: 2px;
        right: 4px;
        padding-right: 15px;
        display: block;
        width: 20px;
        box-sizing: content-box;
        height: 50px;
        z-index: 1;
        color: #7f6921;
      }
      .booking_form input[type='submit'] {
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        font-size: 0.9375rem;
        border: 0;
        height: 50px;
        cursor: pointer;
        outline: none;
        width: 100%;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
        background-color: #4b514d;
        margin-right: 5px;
      }
      @media (max-width: 991px) {
        .booking_form input[type='submit'] {
          margin: 20px 0 0 0;
          -webkit-border-radius: 3px;
          -moz-border-radius: 3px;
          -ms-border-radius: 3px;
          border-radius: 3px;
        }
      }
      .booking_form input[type='submit']:hover {
        background-color: #7f6921;
        color: #fff;
      }
      
      /* Quantity incrementer input */
      .qty-buttons {
        position: relative;
        width: 100%;
        display: inline-block;
      }
      .qty-buttons label {
        position: absolute;
        color: #999;
        left: 15px;
        top: 13px;
        font-weight: 500;
        color: #6c757d;
        font-size: 15px;
        font-size: 0.9375rem;
      }
      .qty-buttons input.qty {
        width: 100%;
        text-align: left;
        padding-left: 80px;
      }
      .qty-buttons input.qtyminus,
      .qty-buttons input.qtyplus {
        position: absolute;
        width: 36px;
        height: 36px;
        border: 0;
        outline: none;
        cursor: pointer;
        -webkit-appearance: none;
        text-indent: -9999px;
        box-shadow: none;
        border-radius: 50%;
        top: 8px;
      }
      .qty-buttons input.qtyplus {
        background: #f5f5f5 url('/assets/images/plus.svg') no-repeat center center;
        right: 15px;
      }
      .qty-buttons input.qtyminus {
        background: #f5f5f5 url('/assets/images/minus.svg') no-repeat center center;
        right: 55px;
      }
      .qty-buttons.version_2 .form-control {
        height: 50px;
      }
      .qty-buttons.version_2 input.qty {
        padding-left: 15px;
      }
      .qty-buttons.version_2 input.qtyplus {
        background: #fff url('/assets/images/plus.svg') no-repeat center center;
        right: 5px;
      }
      .qty-buttons.version_2 input.qtyminus {
        background: #fff url('/assets/images/minus.svg') no-repeat center center;
        right: 40px;
      }
      
      .intro em {
        font-family: 'Caveat', cursive;
        font-size: 32px;
        font-size: 2rem;
        font-style: normal;
        color: #555;
      }
    </style>
@endsection

@section('content')
<div class="row justify-content-center slide-animated three">
    <form id="checkAvailabilityForm">
        <div class="row g-0 booking_form">
            <div class="col-lg-3 ">
                <div class="form-group">
                    <input class="form-control" id="checkIn" type="date" name="check_in" required placeholder="Check in / Check out">
                </div>
            </div>
            <div class="col-lg-3 ">
                <div class="form-group">
                    <input class="form-control" id="checkOut" type="date" name="check_out" required placeholder="Check in / Check out">
                </div>
            </div>
            <div class="col-lg-2 ">
                <select class="p-0 border-0 form-control" name="room_type" id="roomType">
                    <option value="">{{ __('Room Type') }}</option>
                    @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-sm-6 pe-lg-0 pe-sm-1">
                <div class="qty-buttons">
                    <label>{{ __('People') }}</label>
                    <input type="number" name="people" id="people" min="1" value="1" class="qty form-control">
                </div>
            </div>
            <div class="col-lg-2">
                <input type="submit" class="btn_search" value="Search">
            </div>
        </div>
    </form>
    
    <div class="container text-center">
        <span class="text-black caveat" style="font-size: 25px;">Powered by <strong><a class="text-black" href="https://ndako.koverae.com">Ndako</a></strong></span>
    </div>
</div>


@endsection
