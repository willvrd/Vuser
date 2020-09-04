{{-- @extends('user::layouts.master') --}}

@extends('layouts.app')

@section('content')

    <permission-index
        :title="{singular:'{{trans_choice('vuser::permissions.title.permission', 1)}}',plural:'{{trans_choice('vuser::permissions.title.permission', 2)}}'}"
        path="{{route(app()->getLocale().'.api.user.permissions.index')}}">
    </permission-index>

@endsection

<!-- User Scripts -->
{{--
@section('scripts-modules')
    <script src="{{ mix('js/user.js') }}"></script>
@stop
--}}
