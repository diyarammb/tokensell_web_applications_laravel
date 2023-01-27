<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h4 class="title nk-modal-title">{{ __("Assign to Group") }}</h4>
            <form action="{{ route('admin.users.group.assign') }}" method="POST" class="form-validate is-alter">
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="form-control-wrap">
                            <label class="form-label" for="group-id">{{ __("Available Groups") }}</label>
                            <input type="hidden" name="uid" value="{{ $user->id }}">
                            <select required name="gid" id="group-id" class="form-control form-select form-select-sm" data-placeholder="{{ __("Please select") }}">
                                <option></option>
                                @foreach($userGroups as $group)
                                    <option value="{{ $group->id }}" @if (data_get($user, 'user_groups')->contains($group->id)) selected @endif>{{ ucfirst($group->label) }}</option>
                                @endforeach
                            </select>
                            <div class="form-note mt-1">{{ __("User will asign to this selected group.") }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button type="button" class="btn btn-primary group-assign">{{ __("Assign User") }}</button>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-dismiss="modal" class="link link-light">{{ __("Cancel") }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
	$(document).on('click', '.group-assign', function() {
		let $self = $(this), $form = $self.parents("form"), url = $form.attr("action"), data = $form.serialize();
        if(url) {
            NioApp.Form.toPost(url, data, { btn: $self });
        }
	});
</script>
