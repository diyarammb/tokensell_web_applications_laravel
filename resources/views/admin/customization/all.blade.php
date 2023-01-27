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
                        <p>{{ __("All list") }}</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <a href="{{ route('admin.csmCustomizationNew') }}" class="btn btn-primary d-none d-sm-inline-flex"><em class="icon ni ni-b-si"></em> <span>{{ __("Add new") }}</span></a>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between g-3">
                <div class="container">
                    <table class="nk-plan-tnx table">
                        <thead class="thead-light">
                            <tr>
                                <th class="tb-col">#</th>
                                <th class="tb-col">Date</th>
                                <th class="tb-col">Algo</th>
                                <th class="tb-col">Active workers</th>
                                <th class="tb-col">Hashrate</th>
                                <th class="tb-col">Network</th>
                                <th class="tb-col tb-col-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @php $i = 1; @endphp
                            @foreach($data as $key => $row)
                            <tr>
                                <td class="tb-col">{{ $i++; }}</td>
                                <td class="tb-col">{{ $row->date }}</td>
                                <td class="tb-col">{{ $row->algo }}</td>
                                <td class="tb-col">{{ $row->active_workers }}</td>
                                <td class="tb-col">{{ $row->hashrate }} PH/s</td>
                                <td class="tb-col">{{ $row->network }} EH/s</td>
                                <td class="tb-col tb-col-end">
                                    <form class="d-inline" method="post" action="delete/{{ $row->id }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="border-0 bg-transparent" onclick="return confirm('Are you sure, you want to delete?');" ><i class="icon ni ni-trash-fill h4"></i></button>
                                    </form>
                                    <a href="update/{{ $row->id }}"><i class="icon ni ni-edit h4"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@push('scripts')
<script src={{asset('assets/js/charts.js') }}></script>
<script src={{asset('assets/js/jquery.dataTables.min.js') }}></script>
@endpush
