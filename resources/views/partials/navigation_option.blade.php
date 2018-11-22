<li class="nav-{{ $option }} {{ Request::is("{$option}*") ? 'active' : '' }}">

    @if ($option == 'settings')
        <a type="button" class="btn btn-default btn-sm pull-right" title="path"
            href="path" target="_blank">
            <i class="fa fa-info-circle" style="width:20px"></i>
        </a>
    @elseif ($option == 'reports')
        <a type="button" class="btn btn-default btn-sm pull-right" title="{{ trans('texts.calendar') }}"
            href="{{ url('/reports/calendar') }}">
            <i class="fa fa-calendar" style="width:20px"></i>
        </a>
	@endif

        <a type="button" class="btn btn-primary btn-sm pull-right"
            href="{{ url("/{$option}/create") }}">
            <i class="fa fa-plus-circle" style="width:20px" title="{{ trans('texts.create_new') }}"></i>
        </a>
    

    <a href="{{ url($option == 'recurring' ? 'recurring_invoice' : $option) }}"
        style="padding-top:6px; padding-bottom:6px"
        class="nav-link {{ Request::is("{$option}*") ? 'active' : '' }}">
        <i class="fa fa-icon" style="width:46px; padding-right:10px"></i>
        tmp&nbsp;<sup>tmp</sup>
    </a>

</li>
