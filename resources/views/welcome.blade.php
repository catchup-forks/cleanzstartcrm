@extends('partials.header')

@section('head')
@parent

<script src="{{ asset('js/daterangepicker.min.js') }}" type="text/javascript"></script>
<link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('content')

<div class="row" id="dashboard-totals-in-all-currencies-help" style="display: none">
    <div class="col-xs-12">
        <div class="alert alert-warning custom-message">{!! trans('texts.dashboard_totals_in_all_currencies_help', [
            'link' => link_to('/settings/invoice_settings#invoice_fields', trans('texts.custom_field'), ['target' =>
            '_blank']),
            'name' => trans('texts.exchange_rate')
            ]) !!}</div>
    </div>
</div>

	<div class="row">
		<div class="col-md-2">
			<ol class="breadcrumb">
				<li class='active'>{{ trans('texts.dashboard') }}</li>
			</ol>
		</div>

		<div class="col-md-10">
            <div class="pull-right">
                <div id="group-btn-group" class="btn-group" role="group" style="border: 1px solid #ccc; margin-left:18px">
                    <button type="button" class="btn btn-normal active" data-button="day" style="font-weight:normal !important;background-color:white">{{
                        trans('texts.day') }}</button>
                    <button type="button" class="btn btn-normal" data-button="week" style="font-weight:normal !important;background-color:white">{{
                        trans('texts.week') }}</button>
                    <button type="button" class="btn btn-normal" data-button="month" style="font-weight:normal !important;background-color:white">{{
                        trans('texts.month') }}</button>
                </div>
                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 9px 14px; border: 1px solid #ccc; margin-top: 0px; margin-left:18px">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body revenue-panel">
                    <div style="overflow:hidden">
                        <div class="headerClass">
						{{--headerClass--}}
                            {{ trans('texts.total_revenue') }}
                        </div>
                        <div class="revenue-div in-bold pull-right" style="color:#337ab7">
                        </div>
                        <div class="in-bold">
                            <div class="currency currency_1" style="display:none">
                                0
                            </div>
                            <div class="currency currency_blank" style="display:none">
                                &nbsp;
                            </div>
                        </div>
						{{--footerClass--}}
                        <div class="range-label-div footerClass pull-right" style="color:#337ab7;font-size:16px;display:none;">
                            {{ trans('texts.last_30_days') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body expenses-panel">
                    <div style="overflow:hidden">
					{{-- $headerClass --}}
                        <div class="headerClass">
                            {{ trans('texts.average_invoice') }}
                        </div>
                        <div class="average-div in-bold pull-right" style="color:#337ab7">
                        </div>
                        <div class="in-bold">

                            <div class="currency currency_1" style="display:none">
                                tmp<br />
                            </div>
                            <div class="currency currency_totals" style="display:none">
                                tmp<br />
                            </div>

                            <div class="currency currency_1" style="display:none">
                                0
                            </div>

                            <div class="currency currency_blank" style="display:none">
                                &nbsp;
                            </div>
                        </div>
						{{-- $footerClass --}}
                        <div class="range-label-div footerClass pull-right" style="color:#337ab7;font-size:16px;display:none;">
                            {{ trans('texts.last_30_days') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body outstanding-panel">
                    <div style="overflow:hidden">
                        <div class="headerClass">
                            {{ trans('texts.outstanding') }}
                        </div>
                        <div class="outstanding-div in-bold pull-right" style="color:#337ab7">
                        </div>
                        <div class="in-bold">
                            <div class="currency currency_1" style="display:none">
                                0
                            </div>
                            <div class="currency currency_blank" style="display:none">
                                &nbsp;
                            </div>
                        </div>
						{{-- $footerClass --}}
                        <div class="range-label-div footerClass pull-right" style="color:#337ab7;font-size:16px;display:none;">
                            {{ trans('texts.last_30_days') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <div id="progress-div" class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
            <canvas id="chart-canvas" height="70px" style="background-color:white;padding:20px;display:none"></canvas>
        </div>
    </div>
    <p>&nbsp;</p>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px">
                <div class="panel-heading">
                    <h3 class="panel-title in-bold-white">
                        <i class="glyphicon glyphicon-exclamation-sign"></i> {{ trans('texts.activity') }}

                        <div class="pull-right" style="font-size:14px;padding-top:4px">
						invoices sent
                        </div>

                    </h3>
                </div>
                <ul class="panel-body list-group" style="height:276px;overflow-y:auto;">

                    <li class="list-group-item">
                        <span style="color:#888;font-style:italic">tmp</span>
                        tmp
                    </li>

                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px;">
                <div class="panel-heading" style="margin:0; background-color: #f5f5f5 !important;">
                    <h3 class="panel-title" style="color: black !important">

                        <div class="pull-right" style="font-size:14px;padding-top:4px;font-weight:bold">

                            <span class="currency currency_1" style="display:none">
                                {{ trans('texts.average_invoice') }}
                                tmp
                            </span>

                            <span class="average-div" style="color:#337ab7" />
                        </div>

                        <i class="glyphicon glyphicon-ok-sign"></i> {{ trans('texts.recent_payments') }}
                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th>{{ trans('texts.invoice_number_short') }}</th>
                            <th>{{ trans('texts.client') }}</th>
                            <th>{{ trans('texts.payment_date') }}</th>
                            <th>{{ trans('texts.amount') }}</th>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>tmp</td>
                                
                                <td>tmp</td>
                                

                                <td>tmp</td>
                                <td>tmp</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px;">
                <div class="panel-heading" style="margin:0; background-color: #f5f5f5 !important;">
                    <h3 class="panel-title" style="color: black !important">
                        <i class="glyphicon glyphicon-time"></i> {{ trans('texts.upcoming_invoices') }}
                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th>{{ trans('texts.invoice_number_short') }}</th>
                            <th>{{ trans('texts.client') }}</th>
                            <th>{{ trans('texts.due_date') }}</th>
                            <th>{{ trans('texts.balance_due') }}</th>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>tmp</td>
                                
                                <td>tmp</td>
                                

                                <td>tmp</td>
                                <td>tmp</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px">
                <div class="panel-heading" style="background-color:#777 !important">
                    <h3 class="panel-title in-bold-white">
                        <i class="glyphicon glyphicon-time"></i> {{ trans('texts.invoices_past_due') }}
                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th>{{ trans('texts.invoice_number_short') }}</th>
                            <th>{{ trans('texts.client') }}</th>
                            <th>{{ trans('texts.due_date') }}</th>
                            <th>{{ trans('texts.balance_due') }}</th>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>tmp</td>
                                
                                <td>tmp</td>
                                

                                <td>tmp</td>
                                <td>tmp</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px;">
                <div class="panel-heading" style="margin:0; background-color: #f5f5f5 !important;">
                    <h3 class="panel-title" style="color: black !important">
                        <i class="glyphicon glyphicon-time"></i> {{ trans('texts.upcoming_quotes') }}
                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th>{{ trans('texts.quote_number_short') }}</th>
                            <th>{{ trans('texts.client') }}</th>
                            <th>{{ trans('texts.valid_until') }}</th>
                            <th>{{ trans('texts.amount') }}</th>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>tmp</td>
                                
                                <td>tmp</td>
                                

                                <td>tmp</td>
                                <td>tmp</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default dashboard" style="height:320px">
                <div class="panel-heading" style="background-color:#777 !important">
                    <h3 class="panel-title in-bold-white">
                        <i class="glyphicon glyphicon-time"></i> {{ trans('texts.expired_quotes') }}
                    </h3>
                </div>
                <div class="panel-body" style="height:274px;overflow-y:auto;">
                    <table class="table table-striped">
                        <thead>
                            <th>{{ trans('texts.quote_number_short') }}</th>
                            <th>{{ trans('texts.client') }}</th>
                            <th>{{ trans('texts.valid_until') }}</th>
                            <th>{{ trans('texts.amount') }}</th>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>tmp</td>
                                
                                <td>tmp</td>
                                

                                <td>tmp</td>
                                <td>tmp</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@stop {{--section('content')--}}