@extends('user.layouts.master')

@section('title', __('Dashboard'))

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-head-sub"><span>{{ __('Production Area') }}</span></div>
        <div class="nk-block-between-md g-4">
            <div class="nk-block-head-content">
                <h2 class="nk-block-title fw-normal">{{ (auth()->user()->display_name) ? auth()->user()->display_name : auth()->user()->name }}</h2>
                <div class="nk-block-des">
                    <p>{{ __("Welcome to the production center.Swipe for pool information.") }}</p>
                </div>
            </div>
            
            <div class="nk-block-head-content d-none d-md-inline-flex">
                <ul class="nk-block-tools gx-3">
                    @if (module_exist('FundTransfer', 'mod') && feature_enable('transfer'))
                        <li><a href="{{ route('user.send-funds.show') }}" class="btn btn-light btn-white"><span>{{ __('Send Funds') }}</span> <em class="icon ni ni-arrow-long-right d-none d-lg-inline-block"></em></a></li>
                    @endif
                    @if(has_route('user.investment.invest'))
                    <li><a href="{{ route('user.investment.invest') }}" class="btn btn-secondary"><span>{{ __('Devices') }}</span> <em class="icon ni ni-arrow-long-right d-none d-lg-inline-block"></em></a></li>
                    @endif
                    <li><a href="{{ route('deposit') }}" class="btn btn-primary"><span>{{ __('I am New') }}</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container wide-lg mt-5">
        <div class="my-3">
            <div class="row">
                <h3 class="text-left w-100 mb-3 text-dark">Proof of work</h3>
                <div class="table-responsive">
                    
                    <table class="text-center table">
                        <thead class="thead-light py-2">
                            <tr>
                                <th class="pb-2 pt-3">Coin</th>
                                <th class="pb-2 pt-3">Algo</th>
                                <th class="pb-2 pt-3">Active Workers</th>
                                <th class="pb-2 pt-3">Hashrate</th>
                                <th class="pb-2 pt-3">Network</th>
                                <th class="pb-2 pt-3">Situation</th>
                                <th class="pb-2 pt-3 border-left">Chart</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-bottom">
                                <td class="pb-2 pt-3"><img src="/images/bitcoin.svg"></td>
                                <td class="pb-2 pt-3">{{ $pow_query[0]->algo }}</td>
                                <td class="pb-2 pt-3">{{ $pow_query[0]->active_workers }}</td>
                                <td class="pb-2 pt-3">{{ $pow_query[0]->hashrate }} PH/s</td>
                                <td class="pb-2 pt-3">{{ $pow_query[0]->network }} EH/s</td>
                                <td class="pb-2 pt-3">Intense</td>
                                <td class="pb-2 pt-3 border-left"><button data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="border-0 bg-transparent" id="toggle-show-chart"><i class="icon ni ni-sort-down-fill"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row collapse show w-100" id="collapseExample">
                <div class="row">
                    
                    <div class="col-12 col-sm-6">
                        <p class="pl-4 pt-4 m-0 pb-0 h6"><em class="icon ni ni-dot text-success"></em> Hashrate :</p>
                        <div id="hashrate_chart" style="width: 100%;height:400px;"></div>
                    </div>
                    
                    <div class="col-12 col-sm-6">
                        <p class="pl-0 pt-4 m-0 pb-0 h6">Hashrate profit est.</p>
                        <div class="pl-3 p-3">
                            <div>
                                <label for="hashrate_input">Hashrate</label>
                                <input value="1" class="w-25 mr-2" name="hashrate_input" id="hashrate_input" type="number"> 
                                <span class="pr-2"> TH/s</span> 
                                <span class="pr-2"> &#8776;</span>
                                <span id="hashrate_input_btc" data-value="{{ $pow_query[0]->btc }}" class="pr-2"> {{ $pow_query[0]->btc }} BTC</span>
                                <span class="pr-2"> &#8776;</span>
                                <span id="hashrate_input_usd" data-value="{{ $pow_query[0]->usd }}" class="pr-2"> {{ $pow_query[0]->usd }} USD</span>
                                <br>
                                <!--<label for="hashrate_input">Fee</label>-->
                                <!--<input class="w-25 mr-2" name="hashrate_input" id="hashrate_input" type="text" value="0"> -->
                                <!--<span class="pr-2"> %</span> -->
                                <!--<span class="pr-2"> </span> -->
                                <!--<span class="pr-2"> Earning Models</span>-->
                                <!--<label checked for="earning_models_input1" class="pr-2"><input name="earning_models_input" id="earning_models_input1" type="radio"> FPPS</label>-->
                                <!--<label for="earning_models_input2" class="pr-2"><input name="earning_models_input" id="earning_models_input2" type="radio"> PPS</label>-->
                            </div>
                        </div>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="border-0">Difficulty</th>
                                    <th class="border-0">Next difficulty esitamed</th>
                                    <th class="border-0">Date to next difficulty</th>
                                    <th class="border-0">Algorithm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $pow_query[0]->difficulty }}</td>
                                    <td>{{ $pow_query[0]->nextdifficulty }}</td>
                                    <td>{{ $pow_query[0]->datetonext }}</td>
                                    <td>{{ $pow_query[0]->algo }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <table class="table">
                            <tbody>
                                <tr class="py-0">
                                    <td class="border-0 py-0">Settlement period</td>
                                    <td class="text-dark border-0 py-0">00:00 today to 00:00 UTC tomorrow</td>
                                </tr>
                                <tr class="p-0">
                                    <td class="border-0 py-0">Payout time</td>
                                    <td class="text-dark border-0 py-0">02:00 to 10:00 UTC daily</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(has_restriction())
    <div class="nk-block">
        <div class="alert alert-danger bg-white alert-thick">
            <div class="alert-cta flex-wrap flex-md-nowrap g-2">
                <div class="alert-text has-icon">
                    <em class="icon ni ni-report-fill text-danger fdf"></em>
                    <p class="text-base"><strong>{{ __("Caution") }}:</strong> {{ 'All the transactions are NOT real as you have logged into demo application to see the platform.' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {!! Panel::profile_alerts() !!}

    <!-- 3 colume Available Balance <div class="nk-block">
        <div class="row g-gs">
            <div class="col-md-4">
                {!! Panel::balance('account', ['cta' => true]) !!}
            </div>
            <div class="col-md-4">
                {!! Panel::balance('deposit') !!}
            </div>
            <div class="col-md-4">
                {!! Panel::balance('withdraw') !!}
            </div>
        </div>
    </div>-->

    @if (filled($recentTransactions))
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head-sm">
            <div class="nk-block-between-md g-4">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title title">{{ __('Extract Of Account') }}</h5>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ route('transaction.list') }}">{{ __('More Details') }}</a>
                </div>
            </div>
        </div>
        <div class="nk-odr-list card card-bordered">
            @foreach($recentTransactions as $transaction)
                @include('user.transaction.trans-row', compact('transaction'))
            @endforeach
        </div>
    </div>
    @endif

    {!! Panel::referral('invite-card') !!}

    {!! Panel::cards('support') !!}

    @if(Panel::news()) 
    <div class="nk-block">
        <div class="card card-bordered d-xl-none">
            <div class="card-inner card-inner-sm">
                {!! Panel::news() !!}
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@if (filled($recentTransactions))
    @push('modal')
    <div class="modal fade" role="dialog" id="ajax-modal"></div>
    @endpush
@endif


@push('scripts')

<script type="text/javascript">
        // Initialize the echarts instance based on the prepared dom
        var myChart = echarts.init(document.getElementById('hashrate_chart'));
        var option = {
            
            xAxis: {
                boundaryGap: false,
                type: 'category',
                data: [<?php echo $chart_data['label']; ?>]
            },
            yAxis: {
                type: 'value',
                axisTick: {
                    inside: true
                }
            },
            tooltip: {
                trigger: 'axis',
                formatter: '{b}: <hr class="m-0 p-0 mb-1"/><em class="icon ni ni-dot text-primary"></em> Hashrate {c} PH/s'
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            series: [
                {
                    type: 'line',
                    data: [{{ $chart_data['value'] }}],
                    
                    name: 'Hashrate',
                    smooth: true,
                    showSymbol: false,
                    stack: 'b',
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        {
                            offset: 0,
                            color: 'rgba(58,77,233,0.8)'
                        },
                        {
                            offset: 1,
                            color: 'rgba(58,77,233,0.1)'
                        }
                        ])
                    },
                },
            ],
        };
        myChart.setOption(option);
        
        window.addEventListener('resize',function(){
          myChart.resize();
        })
        
        var btc = '{{ $pow_query[0]->btc }}';
        var usd = '{{ $pow_query[0]->usd }}';
        $(document).on('keyup', '#hashrate_input', function(){
            let hashrate_input = $(this).val();
            // let hashrate_input_btc = 0.00000448*hashrate_input;
            let hashrate_input_btc = btc*hashrate_input;
            let hashrate_input_usd = usd*hashrate_input;
            $('#hashrate_input_btc').html(+hashrate_input_btc.toFixed(8) + ' BTC');
            $('#hashrate_input_usd').html(+hashrate_input_usd.toFixed(3) + ' USD');
        })
        
        $(document).ready(function(){
            
        });
        
    </script>
@endpush