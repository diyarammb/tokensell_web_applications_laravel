@extends('frontend.layouts.master')

@section('title', __(gss('front_page_title', "Welcome")))
@section('desc', gss('seo_description_home', gss('seo_description', '')))
@section('keyword', gss('seo_keyword_home', gss('seo_keyword', '')))

@php

$leadText = gss('iv_plan_pg_text', "Here is our several investment plans. You can invest daily, weekly or monthly and get higher returns in your investment.");
$leadTitle = gss('iv_plan_pg_title', "Choose your favourite plan and start earning now.");

@endphp

@section('content')
<div class="nk-block-head nk-block-head-lg text-center">
    <h2 class="nk-block-title fw-normal">{{ (gss('iv_plan_pg_heading')) ? __(gss('iv_plan_pg_heading')) : __('Investment Plans') }}</h2>
    @if ($leadText || $leadTitle)
    <div class="nk-block-des w-max-550px m-auto">
        @if ($leadText)
        <p class="lead">{{ __($leadText) }}</p>
        @endif
        @if ($leadTitle)
        <p><strong>{{ __($leadTitle) }}</strong></p>
        @endif
    </div>
    @endif
</div>
@if(!empty($schemes))
<div class="nk-block">
    <div class="row justify-content-center g-gs">
        @foreach($schemes as $ivp)
        <div class="col-md-6 col-lg-4">
            <div class="nk-ivp-card card card-bordered card-full">
                
                @if($ivp->image != NULL)
                <img width="200" height="200" class="card-img-top" src="/images/invest-img/{{$ivp->image}}"  alt="{{ data_get($ivp, 'name') }}">
                @else
                    <img class="card-img-top" src="/images/invest-img/place-holder.png"  alt="{{ data_get($ivp, 'name') }}">
                @endif
                
                <div class="card-inner-group">
                    <div class="card-inner">
                        <div class="nk-ivp-title card-title">
                            <h4 class="title">{{ data_get($ivp, 'name') }}</h4>
                            @if(sys_settings('iv_plan_desc_show') == 'yes')
                            <p class="sub-text">{{ data_get($ivp, 'desc') }}</p>
                            @endif
                        </div>
                        <div class="nk-ivp-summary card-text">
                            <div class="row">
                                <div class="col-6">
                                    <!--<span class="lead-text"><span class="small text-dark">{{ data_get($ivp, 'rate_text') }}</span></span>-->
                                    <span class="lead-text"><span class="small text-dark"> {{ data_get($ivp, 'ths') }}</span></span>
                                   <!-- <span class="sub-text"style="text-transform: uppercase;">{{ __(':period Interest', ['period' => ucfirst(__(data_get($ivp, 'calc_period')))]) }}</span>-->
                                    <span class="sub-text">{{ __('Capacity') }}</span>
                                
                                </div>
                                <div class="col-6">
                                    <span class="lead-text"><span class="small text-dark">{{ data_get($ivp, 'term') }}</span></span>
                                    <span class="sub-text">{{ __('Term :type', ['type' => __(ucfirst(data_get($ivp, 'term_type')))]) }} Contract</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @dd($ivp) --}}
                    <div class="card-inner">
                        <div class="card-text">
                            <ul class="nk-ivp-data">
                                @if(data_get($ivp, 'is_fixed'))
                                <li><span class="label">{{ __('Contract Price') }}</span> - <span class="data fw-medium text-dark">{{ money(data_get($ivp, 'amount'), base_currency()) }}</span></li>
                                <li><span class="label">{{ __('Device Type') }}</span> - <span class="data">{{ __("Asic") }}</span></li>
                                @else
                                <li><span class="label">{{ __('Min Deposit') }}</span> - <span class="data fw-medium text-dark">{{ money(data_get($ivp, 'amount'), base_currency()) }}</span></li>
                                <li><span class="label">{{ __('Max Deposit') }}</span> - <span class="data">{{ data_get($ivp, 'maximum') ? money(data_get($ivp, 'maximum'), base_currency()) : __("Unlimited") }}</span></li>
                                @endif
                                @if(sys_settings('iv_plan_terms_show') == 'yes')
                                <li><span class="label">{{ __('Term Duration') }}</span> - <span class="data">{{ data_get($ivp, 'term_text_alter') }}</span></li>
                                @endif
                                @if(sys_settings('iv_plan_payout_show') == 'yes')
                                <li><span class="label">{{ __('Payout Term') }}</span> - <span class="data">{{ data_get($ivp, 'payout') == 'after_matured' ? __("After matured") : __("Term basis") }}</span></li>
                                @endif
                                @if(sys_settings('iv_plan_capital_show') == 'yes')
                                <li><span class="label">{{ __('Contract Type') }} <em class="icon ni ni-info text-soft nk-tooltip small" title="{{ __("The invested amount will be return at the end of term.") }}"></em></span> - <span class="data">@if(data_get($ivp, 'capital')) {{ __('End of Term') }} @else {{ __('Time Based') }} @endif</span></li>
                                @endif
                                @if(sys_settings('iv_plan_total_percent') == 'yes')
                                <!--<li><span class="label">{{ __('Total Return') }}</span> - <span class="data">{{ data_get($ivp,'total_return') }}%</span></li>-->
                                @endif
                                <li><span class="label">{{ __('Last 28 Days') }} </span> - <span class="data">{{ data_get($ivp, 'lastdays')}}</span></li>
                                <li><span class="label">{{ __('Last 24 Hours') }} </span> - <span class="data">{{ data_get($ivp, 'lasttimeandday')}}</span></li>
                            </ul>
                            @if(!auth()->check())
                            <div class="nk-ivp-action">
                                <a class="btn btn-primary" href="{{ route('auth.login.form') }}"><span>{{ __('Invest Now') }}</span></a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection