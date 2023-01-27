@extends('admin.layouts.master')

@section('title', __('Admin Dashboard'))

@php

use App\Enums\TransactionCalcType;
use App\Enums\TransactionType;

$currency = base_currency();

@endphp

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between g-3">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ __("Proof of Work") }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>{{ __("Add new list") }}</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ route('admin.csmCustomizationAll') }}" class="btn btn-primary d-none d-sm-inline-flex"><em class="icon ni ni-b-si"></em> <span>{{ __("View All") }}</span></a>
                </div>
            </div>
        </div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between g-3">
                <div class="container">
                <form method="POST" action="">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="date" class="m-0 form-label">
                                {{ __("Date :") }}
                            </label>
                            <input name="date" class="form-control" type="date" id="date">
                          </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="algo" class="m-0 form-label">
                                {{ __("Algo :") }}
                            </label>
                            <input name="algo" placeholder="Algo" value="SHA256" class="form-control" type="text" id="algo">
                          </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="workers" class="m-0 form-label">
                                {{ __("Active workers :") }}
                            </label>
                            <input name="workers" placeholder="Workers" class="form-control" type="number" id="workers">
                          </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="hashrate" class="m-0 form-label">
                                {{ __("Hashrate :") }}
                            </label>
                            <input name="hashrate" placeholder="Hashrate" step="0.01" class="form-control" type="number" id="hashrate">
                          </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="network" class="m-0 form-label">
                                {{ __("Network :") }}
                            </label>
                            <input name="network" placeholder="Network" step="0.01" class="form-control" type="number" id="network">
                          </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="btc" class="m-0 form-label">
                                {{ __("BTC :") }}
                            </label>
                            <input name="btc" placeholder="BTC"  class="form-control" type="text" id="btc">
                          </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="usd" class="m-0 form-label">
                                {{ __("USD :") }}
                            </label>
                            <input name="usd" placeholder="USD"  class="form-control" type="text" id="usd">
                          </div>
                        </div>     
                          <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="difficulty" class="m-0 form-label">
                                {{ __("Difficulty :") }}
                            </label>
                            <input name="difficulty" placeholder="Difficulty"  class="form-control" type="text" id="difficulty">
                          </div>
                        </div>  
                          <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="nextdifficulty" class="m-0 form-label">
                                {{ __("Next difficulty esitamed :") }}
                            </label>
                            <input name="nextdifficulty" placeholder="Next difficulty esitamed"  class="form-control" type="text" id="Next_difficulty">
                          </div>
                        </div>  
                          <div class="col-12 col-md-6 mb-2">
                          <div class="form-group">
                            <label for="datetonext" class="m-0 form-label">
                                {{ __("Date to next difficulty :") }}
                            </label>
                            <input name="datetonext" placeholder="Date to next difficulty"  class="form-control" type="text" id="Date_to_next">
                          </div>
                        </div>  
                        
                        
                    </div>
                    <br>
                    <br>
                    <hr>
                    <button type="submit" class="btn btn-primary btn-lg">Insert</button>
                </form>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@push('scripts')
<script src={{asset('assets/js/charts.js') }}></script>
@endpush
