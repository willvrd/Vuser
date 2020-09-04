@can("user.users.index")
<li class="nav-item dropdown">

    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ __('vuser::common.module') }}
    </a>

    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

        @can("user.users.index")
            <a class="dropdown-item" href="{{route(app()->getLocale().'.admin.vuser.users.index')}}">
                {{ trans_choice('vuser::users.title.user', 2) }}
            </a>
        @endcan

        @can("user.roles.index")
            <a class="dropdown-item" href="{{route(app()->getLocale().'.admin.vuser.roles.index')}}">
                {{ trans_choice('vuser::roles.title.role', 2) }}
            </a>
        @endcan

        @can("user.permissions.index")
            <a class="dropdown-item" href="{{route(app()->getLocale().'.admin.vuser.permissions.index')}}">
                {{ trans_choice('vuser::permissions.title.permission', 2) }}
            </a>
        @endcan
        {{--
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route(app()->getLocale().'.admin.vuser.passport.index')}}">
            {{ __('vuser::passport.title.passport') }}
        </a>
        --}}

    </div>

</li>
@endcan
