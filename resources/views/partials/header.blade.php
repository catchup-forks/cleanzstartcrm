@extends('master')

@section('head_css')
<link href="{{ asset('css/built.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('head')

<script type="text/javascript">
    function checkForEnter(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            validateServerSignUp();
            return false;
        }
    }

    function logout(force) {
        $('#logoutModal').modal('show');
    }

    function hideMessage() {
        $('.alert-info').fadeOut();
        $.get('/hide_message', function (response) {
            console.log('Reponse: %s', response);
        });
    }

    function openTimeTracker() {
        var width = 1060;
        var height = 700;
        var left = (screen.width / 2) - (width / 4);
        var top = (screen.height / 2) - (height / 1.5);
        window.open("{{ url('/time_tracker') }}", "time-tracker", "width=" + width + ",height=" + height +
            ",scrollbars=no,toolbar=no,screenx=" + left + ",screeny=" + top +
            ",location=no,titlebar=no,directories=no,status=no,menubar=no");
    }

    window.loadedSearchData = false;

    function onSearchBlur() {
        $('#search').typeahead('val', '');
    }

    $(function () {
        // auto-hide status alerts
        window.setTimeout(function () {
            $(".alert-hide").fadeOut();
        }, 3000);

        /* Set the defaults for Bootstrap datepicker */
        $.extend(true, $.fn.datepicker.defaults, {
            weekStart: 0
        });

        $('ul.navbar-settings, ul.navbar-search').hover(function () {
            if ($('.user-accounts').css('display') == 'block') {
                $('.user-accounts').dropdown('toggle');
            }
        });

        @yield('onReady')

        // manage sidebar state
        function setupSidebar(side) {
            $("#" + side + "-menu-toggle").click(function (e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled-" + side);

                var toggled = $("#wrapper").hasClass("toggled-" + side) ? '1' : '0';
                $.post('{{ url('save_sidebar_state') }}?show_' + side + '=' + toggled);
            });
        }

        setupSidebar('left');
        setupSidebar('right');

        // auto select focused nav-tab
        if (window.location.hash) {
            setTimeout(function () {
                $('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
            }, 1);
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if (isStorageSupported() && /\/settings\//.test(location.href)) {
                var target = $(e.target).attr("href") // activated tab
                if (history.pushState) {
                    history.pushState(null, null, target);
                }
                if (isStorageSupported()) {
                    localStorage.setItem('last:settings_page', location.href.replace(location.hash, ''));
                }
            }
        });

        // set timeout onDomReady
        setTimeout(delayedFragmentTargetOffset, 500);

        // add scroll offset to fragment target (if there is one)
        function delayedFragmentTargetOffset() {
            var offset = $(':target').offset();
            if (offset) {
                var scrollto = offset.top - 180; // minus fixed header height
                $('html, body').animate({
                    scrollTop: scrollto
                }, 0);
            }
        }

    });
</script>

@stop

@section('body')


<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="height:60px;">

    <div class="navbar-header">
        <div class="pull-left">
            {{-- Per our license, please do not remove or modify this link. --}}
            <img src="{{ asset('images/invoiceninja-logo.png') }}" width="193" height="25" style="float:left" />
        </div>
        <div class="pull-right">
            <a href="#" id="left-menu-toggle" class="menu-toggle" title="{{ trans('texts.toggle_navigation') }}">
                <div class="navbar-brand" style="background-color:red">
                    <i class="fa fa-bars hide-phone" style="width:32px;padding-top:1px;float:left"></i>
                </div>
            </a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1" style="background-color:brown">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
    </div>

    <a id="right-menu-toggle" class="menu-toggle hide-phone pull-right" title="{{ trans('texts.toggle_history') }}"
        style="cursor:pointer">
        <div class="fa fa-bars" style="background-color:yellow"></div>
    </a>

    <div class="collapse navbar-collapse" id="navbar-collapse-1">
        <div class="navbar-form navbar-right">

            tmp

            <div class="btn-group user-dropdown">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <div id="myAccountButton" class="ellipsis" style="max-width:130px;">
                        User Display Name
                        <span class="caret"></span>
                    </div>
                </button>
                <ul class="dropdown-menu user-accounts">
                    tmp
                    <li class="divider"></li>
                   tmp
                    <li>
					{!!link_to('#', trans('texts.add_company'), ['onclick' => 'showSignUp()']) !!}</li>
                    <li>{!! link_to('#', trans('texts.logout'), array('onclick'=>'logout()')) !!}</li>
                </ul>
            </div>

        </div>

        <ul class="nav navbar-nav hide-non-phone" style="font-weight: bold">
                &nbsp; dashboard &nbsp; clients &nbsp; products &nbsp; invoices
                &nbsp; payments &nbsp; recurring_invoices &nbsp; credits &nbsp; quotes
                &nbsp; proposals &nbsp; projects &nbsp; tasks &nbsp; expenses
                &nbsp; vendors &nbsp; reports &nbsp; settings
        </ul>
    </div><!-- /.navbar-collapse -->

</nav>

<div id="wrapper" class='toggled-left toggled-right'>

    <!-- Sidebar -->
    <div id="left-sidebar-wrapper" class="hide-phone">
        <ul class="sidebar-nav sidebar-nav-light">

            @include('partials.navigation_option', ['option' => 'dashboard'])
			@include('partials.navigation_option', ['option' => 'clients'])
			@include('partials.navigation_option', ['option' => 'products'])
			@include('partials.navigation_option', ['option' => 'invoices'])
            @include('partials.navigation_option', ['option' => 'payments'])
            @include('partials.navigation_option', ['option' => 'recurring_invoices'])
            @include('partials.navigation_option', ['option' => 'credits'])
            @include('partials.navigation_option', ['option' => 'quotes'])
            @include('partials.navigation_option', ['option' => 'proposals'])
			@include('partials.navigation_option', ['option' => 'projects'])
			@include('partials.navigation_option', ['option' => 'tasks'])
			@include('partials.navigation_option', ['option' => 'expenses'])
            @include('partials.navigation_option', ['option' => 'vendors'])
            @include('partials.navigation_option', ['option' => 'tickets'])
            @include('partials.navigation_option', ['option' => 'credits'])
            @include('partials.navigation_option', ['option' => 'quotes'])
            @include('partials.navigation_option', ['option' => 'reports'])
            @include('partials.navigation_option', ['option' => 'settings'])
            <li style="width:100%;">
                <div class="nav-footer">

                    <a href="javascript:showContactUs()" title="{{ trans('texts.contact_us') }}">
                        <i class="fa fa-envelope"></i>
                    </a>

                    <a href="" target="_blank" title="{{ trans('texts.support_forum') }}">
                        <i class="fa fa-list-ul"></i>
                    </a>
                    <a href="javascript:showKeyboardShortcuts()" title="{{ trans('texts.help') }}">
                        <i class="fa fa-question-circle"></i>
                    </a>
                    <a href="" target="_blank" title="Facebook">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                    <a href="" target="_blank" title="Twitter">
                        <i class="fa fa-twitter-square"></i>
                    </a>
                    <a href="" target="_blank" title="GitHub">
                        <i class="fa fa-github-square"></i>
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <!-- /#left-sidebar-wrapper -->

    <div id="right-sidebar-wrapper" class="hide-phone" style="overflow-y:hidden">
        <ul class="sidebar-nav sidebar-nav-light">
            renderHtml(historybar)
        </ul>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">

            @include('partials.warn_session', ['redirectTo' => '/dashboard'])

            @if (Session::has('warning'))
            <div class="alert alert-warning">{!! Session::get('warning') !!}</div>
            @elseif (env('WARNING_MESSAGE'))
            <div class="alert alert-warning">{!! env('WARNING_MESSAGE') !!}</div>
            @endif

            @if (Session::has('message'))
            <div class="alert alert-info alert-hide" style="z-index:9999">
                {{ Session::get('message') }}
            </div>
            @elseif (Session::has('news_feed_message'))
            <div class="alert alert-info">
                {!! Session::get('news_feed_message') !!}
                <a href="#" onclick="hideMessage()" class="pull-right">{{ trans('texts.hide') }}</a>
            </div>
            @endif

            @if (Session::has('error'))
            <div class="alert alert-danger">{!! Session::get('error') !!}</div>
            @endif

            <div class="pull-right">
                @yield('top-right')
            </div>

            <div class="row"><div class="col-md-12">breadcrumbs</div></div>

            @yield('content')
            <br />
            <div class="row">
                <div class="col-md-12">
                    tmp
                </div>
            </div>
        </div>


        @stack('component_scripts')

        <!-- /#page-content-wrapper -->
    </div>

    @include('modals.contact_us')
    @include('modals.sign_up')
    @include('modals.keyboard_shortcuts')
</div>

<p>&nbsp;</p>


@stop
