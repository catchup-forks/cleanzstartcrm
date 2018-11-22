<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    @if (Session::has('error'))
        <!-- Error: {{ Session::get('error') }} -->
    @endif
    <meta charset="utf-8">


        <title>{{ isset($title) ? ($title . ' | CleanCrm') : ('CleanCrm | ' . trans('texts.app_title')) }}</title>
        <meta name="description" content="{{ isset($description) ? $description : trans('texts.app_description') }}"/>
        <link href="{{ asset('favicon-v2.png') }}" rel="shortcut icon" type="image/png">

        <meta property="og:site_name" content="CleanCrm"/>
        <meta property="og:url" content="{{ SITE_URL }}"/>
        <meta property="og:title" content="CleanCrm"/>
        <meta property="og:image" content="{{ SITE_URL }}/images/round_logo.png"/>
        <meta property="og:description" content="Simple, Intuitive Invoicing."/>

        <!-- http://realfavicongenerator.net -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ url('favicon-32x32.png') }}" sizes="32x32">
        <link rel="icon" type="image/png" href="{{ url('favicon-16x16.png') }}" sizes="16x16">
        <link rel="manifest" href="{{ url('manifest.json') }}">
        <link rel="mask-icon" href="{{ url('safari-pinned-tab.svg') }}" color="#3bc65c">
        <link rel="shortcut icon" href="{{ url('favicon.ico') }}">
        <meta name="apple-mobile-web-app-title" content="CleanCrm">
        <meta name="application-name" content="CleanCrm">
        <meta name="theme-color" content="#ffffff">


    <!-- http://stackoverflow.com/questions/19012698/browser-cache-issues-in-laravel-4-application -->
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="cache-control" content="must-revalidate"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @yield('head_css')

    <script src="{{ asset('js/built.min.js') }}?no_cache=4.5.5" type="text/javascript"></script>

    <script type="text/javascript">
        window.onerror = function (errorMsg, url, lineNumber, column, error) {
            // Less than IE9 https://stackoverflow.com/a/14835682/497368
            if (! document.addEventListener) {
                return;
            }
            try {
                $.ajax({
                    type: 'GET',
                    url: '{{ URL::to('log_error') }}',
                    data: 'error=' + encodeURIComponent(errorMsg + ' | Line: ' + lineNumber + ', Column: '+ column)
                    + '&url=' + encodeURIComponent(window.location)
                });
            } catch (exception) {
                console.log('Failed to log error');
                console.log(exception);
            }

            return false;
        }

        // http://t4t5.github.io/sweetalert/
        function sweetConfirm(successCallback, text, title, cancelCallback) {
            title = title || {!! json_encode(trans("texts.are_you_sure")) !!};
            swal({
                //type: "warning",
                //confirmButtonColor: "#DD6B55",
                title: title,
                text: text,
                cancelButtonText: {!! json_encode(trans("texts.no")) !!},
                confirmButtonText: {!! json_encode(trans("texts.yes")) !!},
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: true,
            }).then(function() {
                successCallback();
                swal.close();
            }).catch(function() {
                if (cancelCallback) {
                    cancelCallback();
                }
            });
        }

        function showPasswordStrength(password, score) {
            if (password) {
                var str = {!! json_encode(trans('texts.password_strength')) !!} + ': ';
                if (password.length < 8 || score < 50) {
                    str += {!! json_encode(trans('texts.strength_weak')) !!};
                } else if (score < 75) {
                    str += {!! json_encode(trans('texts.strength_good')) !!};
                } else {
                    str += {!! json_encode(trans('texts.strength_strong')) !!};
                }
                $('#passwordStrength').html(str);
            } else {
                $('#passwordStrength').html('&nbsp;');
            }
        }

        /* Set the defaults for DataTables initialisation */
        $.extend(true, $.fn.dataTable.defaults, {
            "bSortClasses": false,
            "sDom": "t<'row-fluid'<'span6 dt-left'i><'span6 dt-right'p>>l",
            "sPaginationType": "bootstrap",
            "bInfo": true,
            "oLanguage": {
                'sEmptyTable': "{{ trans('texts.empty_table') }}",
                'sInfoEmpty': "{{ trans('texts.empty_table_footer') }}",
                'sLengthMenu': '_MENU_ {{ trans('texts.rows') }}',
                'sInfo': "{{ trans('texts.datatable_info', ['start' => '_START_', 'end' => '_END_', 'total' => '_TOTAL_']) }}",
                'sSearch': ''
            }
        });
    </script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    @yield('head')

</head>

<body class="body">

@if (request()->phantomjs)
    <script>
        function trackEvent(category, action) {
        }
    </script>
@else
    <script>
        function trackEvent(category, action) {
        }
    </script>
@endif

@yield('body')

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('[data-toggle="tooltip"]').tooltip();

        @if (Session::has('onReady'))
        {{ Session::get('onReady') }}
        @endif
    });

</script>

</body>
</html>
