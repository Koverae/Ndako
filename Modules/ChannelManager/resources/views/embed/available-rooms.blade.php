@extends('channelmanager::layouts.app')

@section('styles')
    <style>
    </style>
@endsection

@section('content')
    <div class="container">
        <span>{{ $rooms->count() }} {{ __('rooms are available for the selected dates!') }}</span>
        @forelse ($rooms as $room)
        <div class="room">
            <h3>{{ $room->name }} ~ {{ $room->unitType->name }}</h3>
            <p>Capacity: {{ $room->capacity }} people</p>
            <p>Price: ${{ format_currency($room->unitType->price) }}</p>
            <button class="select-room-btn" data-room-id="{{ $room->id }}">Select Room</button>
        </div>
        @empty
            <p>No availability for the selected dates</p>
        @endforelse
    </div>
@endsection
