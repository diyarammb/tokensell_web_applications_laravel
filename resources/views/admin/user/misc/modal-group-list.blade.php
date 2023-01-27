<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h4 class="title nk-modal-title">{{ __("Group List") }}</h4>
            @if (filled($userGroups))
                <div class="card card-bordered card-stretch">
                    <div class="nk-tb-list nk-tb-ugplist is-compact">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>{{ __('Label') }}</span></div>
                            <div class="nk-tb-col"><span>{{ __('Description') }}</span></div>
                            <div class="nk-tb-col text-center"><span>{{ __('Color') }}</span></div>
                            <div class="nk-tb-col text-center"><span>{{ __('Status') }}</span></div>
                        </div>
                        
                        @foreach ($userGroups as $group)
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <span class="tb-lead">{{ ucfirst($group->label) }}</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-lead">{{ $group->desc ?? __('Not added yet.') }}</span>
                                </div>
                                <div class="nk-tb-col text-center">
                                    <span class="tb-lead text-{{ $group->color }}"><em class="icon ni ni-layer"></em></span>
                                </div>
                                <div class="nk-tb-col text-center">
                                    <span class="tb-lead {{ $group->status == 'show' ? ' text-success' : ' text-gray' }}">{{ ucfirst($group->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <div class="alert-cta flex-wrap flex-md-nowrap">
                        <div class="alert-text">
                            <p>{{ __('No groups has been added yet.') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
