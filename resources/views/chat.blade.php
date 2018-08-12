@extends('layouts.app')

@section('content')
<div id='app'>
    <div class="container">
        <div class="row justify-content-center">
            <chat-component></chat-component>
            <user-component></user-component>
        </div>
    </div
</div>
@endsection

@section('scripts')
<script>
    window.Laravel = {!! json_encode([
        'csrfToken'=> csrf_token(),
        'user'=> [
            'authenticated' => auth()->check(),
            'id' => auth()->check() ? auth()->user()->id : null,
            'name' => auth()->check() ? auth()->user()->name : null, 
            ]
        ])
    !!};
</script>
@endsection