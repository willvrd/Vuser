{{-- @extends('user::layouts.master') --}}

@extends('layouts.app')

@section('content')

    <user-index
        :title="{singular:'{{trans_choice('vuser::users.title.user', 1)}}',plural:'{{trans_choice('vuser::users.title.user', 2)}}'}"
        path="{{route(app()->getLocale().'.api.user.users.index')}}">
    </user-index>

{{--
    <transition name="slide-fade" mode="out-in">
        <router-view :key="$route.fullPath"></router-view>
    </transition>
--}}


@endsection

<!-- User Scripts -->
{{--
@section('scripts-modules')
    <script src="{{ mix('js/user.js') }}"></script>
@stop
--}}
