{{-- @extends('user::layouts.master') --}}

@extends('layouts.app')

@section('content')

    <role-index
        :title="{singular:'{{trans_choice('vuser::roles.title.role', 1)}}',plural:'{{trans_choice('vuser::roles.title.role', 2)}}'}"
        path="{{route(app()->getLocale().'.api.user.roles.index')}}">
    </role-index>

@endsection

<!-- User Scripts -->
{{--
@section('scripts-modules')
    <script src="{{ mix('js/vuser.js') }}"></script>
@stop
--}}
