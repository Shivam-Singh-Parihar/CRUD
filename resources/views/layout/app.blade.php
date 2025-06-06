<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
      {{--
        This supports:
        1. Blade views using @section('title', 'Page Title')
        2. Livewire route components using ->layout() and ->layoutData()
    --}}
    <title>{{ $title ?? View::getSection('title') ?? 'Default Title' }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body>

    {{--
        This section is used for normal Laravel Blade views.
        Blade views that use @extends('layouts.app') and define @section('main-content')
        will inject their content here.
    --}}
    @yield('main-content')

    {{--
        This section is used by Livewire when rendering route-based components.
        It will output the content defined in the Livewire view when using:
        ->layout('layouts.app')
    --}}
    {{ $slot ?? '' }}

    {{--
        Livewire scripts are required for Livewire components to function properly.
        Always include this before the closing </body> tag.
    --}}
    @livewireScripts

</body>


</html>
