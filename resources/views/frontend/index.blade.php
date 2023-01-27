@extends('frontend.layouts.master')

@section('title', __(gss('front_page_title', "Welcome")))
@section('desc', gss('seo_description_home', gss('seo_description', '')))
@section('keyword', gss('seo_keyword_home', gss('seo_keyword', '')))

@section('content')
@if(!empty($schemes))
<section class="section {{ (gss('ui_page_skin', 'dark') == 'dark') ? 'bg-grad-stripe-botttom' : 'bg-white' }} pb-1 pt-0">
    
    <div style="background: url(/images/b323463.svg); background-position: center center; background-size: cover">
        <div class="container wide-lg py-3">
            <div class="py-5">
                <h2 class="text-uppercase text-light">Miner Social</h2>
                <br>
                <h5 class="text-light">Miner Social, the social pool for all of us.</h5>
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
                                <th class="pb-2 pt-3">Tutorial</th>
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
                                <td class="pb-2 pt-3">View tutorial</td>
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
    <div class="bg-light-gray py-4">
        <div class="container py-3 wide-lg">
            <div class="">
                <div class="row">
                    <div class="col-12 col-md-8 mb-3">
                        <h2 class="text-dark mb-4 mt-5">About Us</h2>
                        <p class="w-100 h6 fw-light w-md-50 text-dark">The Ultimus Pool is a brand new full featured mining pool solution designed to provide easy to use, reliable, profitable, and compliant services for global professional miners.</p>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4 p-3">
                                <div class="text-center">
                                    <img src="/images/5f57aa9.svg" alt="" class="img-fluid text-center">
                                    <p class="text-small">Ultimus pool empowers miners with industry leading functionalities including user created external wallets, sub account and main account structures, flexible pool fee adjustments and energy audit-based incentives for green miners. Our team consists of seasoned professionals committed to providing best in class services for decentralized networks.</p>
                                </div>
                            </div>
                            <div class="col-md-4 p-3">
                                <div class="text-center">
                                    <img src="/images/022c4e1.svg" alt="" class="img-fluid text-center">
                                    <p class="text-small">As part of the commitment to revolutionizing the pool industry, UltimusPool is partnered with CCA and the Energy Web Blockchain and pushing for a new renewable standard for other mining pools to follow around the world.</p>
                                </div>
                            </div>
                            <div class="col-md-4 p-3">
                                <div class="text-center">
                                    <img src="/images/308b7d5.svg" alt="" class="img-fluid text-center">
                                    <p class="text-small">We are also a key strategic business partner of the Binance Pool and by leveraging our partnerships, Ultimus Pool aims to democratize mining by making mining accessible to everyone and providing users with a seamless mining experience.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container wide-lg py-5">
        <div class="">                
            <div class="row">
                <div class="col-12 col-md-8 mb-5">
                    <h1 class="h3 text-dark">Why Choose ULTIMUSPOOL?</h1>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3 p-3">
                            <div class="text-center">
                                <img src="/images/5f57ab9.svg" alt="" class="img-fluid text-center">
                                <h5 class="mb-3 fw-normal mt-4">Easy, Safe and Effective</h5>
                                <p class="text-small">Ultimus makes it easy to sign up and start hashing immediately with a simple signup process and easy to use accounting and management tools.</p>
                            </div>
                        </div>
                        <div class="col-md-3 p-3">
                            <div class="text-center">
                                <img src="/images/94b15ad.svg" alt="" class="img-fluid text-center">
                                <h5 class="mb-3 fw-normal mt-4">Consistent High Earnings</h5>
                                <p class="text-small">Ultimus utilizes the popular FPPS, PPS+ model for maximum user earnings and supports daily payouts. Ultimus works with top institutional players to guarantee and safeguard user assets and ensure consistent payouts.</p>
                            </div>
                        </div>
                        <div class="col-md-3 p-3">
                            <div class="text-center">
                                <img src="/images/945d639.svg" alt="" class="img-fluid text-center">
                                <h5 class="mb-3 fw-normal mt-4">Comprehensive Incentives</h5>
                                <p class="text-small">Ultimus is designed with the greater good in mind, and offers comprehensive incentives for miners using renewable energy and those who want to lower their carbon footprint. Plus, VIP rates are available starting at >5 PH.</p>
                            </div>
                        </div>
                        <div class="col-md-3 p-3">
                            <div class="text-center">
                                <img src="/images/186bb59.svg" alt="" class="img-fluid text-center">
                                <h5 class="mb-3 fw-normal mt-4">Go Green</h5>
                                <p class="text-small">UltimusPool believes in a green Bitcoin future. Ultimus is offering pool fee reductions and other incentives for miners that are utilizing renewable energy sources.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- .container --}}
    
</section>
@endif

@if(gss('front_page_extra', 'on')=='on' && (!auth()->check() || (auth()->check() && auth()->user()->role=='user')))
<section class="section bg-white">

    <div class="container wide-lg my-4">
        <h3 class="mb-5 text-dark">Contact</h3>
        <div class="row mx-0">
            
            <div class="card card-body bg-light-gray">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 text-center">
                        <div class="text-left w-50 m-md-auto d-none d-md-block">
                            <div>
                                <img width="30" src="/images/d7555a11.svg" alt="" class="img-fluid text-center mr-3">
                                <a href="mailto:service@minersocial.com">service@minersocial.com</a>
                            </div>
                            <div>
                                <img width="30" src="/images/63be5b7.svg" alt="" class="img-fluid text-center mr-3">
                                <a href="https://t.me/minersocial">https://t.me/minersocial</a>
                            </div>
                            <div>
                                <img width="30" src="/images/3e5503d.svg" alt="" class="img-fluid text-center mr-3">
                                <a href="https://twitter.com/minersocial">https://twitter.com/minersocial</a>
                            </div>
                        </div>
                        <div class="text-left d-md-none">
                            <div>
                                <img width="30" src="/images/d7555a11.svg" alt="" class="img-fluid text-center mr-3">
                                <a href="mailto:service@minersocial.com">service@minersocial.com</a>
                            </div>
                            <div>
                                <img width="30" src="/images/63be5b7.svg" alt="" class="img-fluid text-center mr-3">
                                <a href="https://t.me/minersocial">https://t.me/minersocial</a>
                            </div>
                            <div>
                                <img width="30" src="/images/3e5503d.svg" alt="" class="img-fluid text-center mr-3">
                                <a href="https://twitter.com/minersocial">https://twitter.com/minersocial</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 text-center">
                        <img src="/images/d7555a1.svg" alt="" class="img-fluid text-center mr-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>
@else
<div class="gap gap-lg"></div>
@endif

@endsection


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