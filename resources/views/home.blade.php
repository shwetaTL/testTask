@extends('layouts.app')

@section('content')

  <div class="container">
     <x-dashboard />
     <x-network_connections :suggestions='$suggestions'/>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
          integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/helper.js') }}?v={{ time() }}" defer></script>
  <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>

@endsection
