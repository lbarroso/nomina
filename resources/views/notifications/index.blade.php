@extends('layouts.main')

@section('content')

    <div class="container">

        <h2> Avisos </h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(Auth::user()->email == 'luis.rey.cien@gmail.com')
            <form action="{{ route('notifications.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Notification Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Notification</button>
            </form>
        @endif

        <hr>

        <ul class="list-group">

            @foreach($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $notification->title }}
                    @if($notification->status == 'unread')
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">Mark as Read</button>
                        </form>
                    @else
                        <span class="badge badge-secondary">Read</span>
                    @endif
                </li>
            @endforeach

        </ul>

    </div>

@endsection
