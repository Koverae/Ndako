@extends('channelmanager::layouts.app')

@section('styles')
<style>
    .room-container {
        margin-top: 2rem;
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }

    .room-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #f0f0f0;
    }

    .room-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .room-card h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .room-card p {
        font-size: 0.95rem;
        margin: 0.25rem 0;
        color: #555;
    }

    .select-room-btn {
        margin-top: 1rem;
        background-color: #7f6921;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .select-room-btn:hover {
        background-color: #5d4f17;
    }

    .availability-banner {
        font-size: 1.2rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        text-align: center;
        color: #2f2f2f;
    }
</style>
@endsection

@section('content')
    <div class="container">
        <div class="availability-banner">
            {{ $rooms->count() }} {{ __('room(s) available for the selected dates!') }}
        </div>

        <div class="room-container">
            @forelse ($rooms as $room)
                <div class="room-card">
                    <h3>{{ $room->name }} <span class="text-muted">({{ $room->unitType->name }})</span></h3>
                    <p><strong>Capacity:</strong> {{ $room->capacity }} people</p>
                    <p><strong>Price:</strong> {{ number_format($room->unitType->getDefaultRate($room->unitType->id)->price, 2) }}</p>
                    <button class="select-room-btn" data-room-id="{{ $room->id }}">Select Room</button>
                </div>
            @empty
                <p class="text-center text-muted">No availability for the selected dates</p>
            @endforelse
        </div>
    </div>
@endsection
