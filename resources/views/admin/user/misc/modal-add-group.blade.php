<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h4 class="title nk-modal-title">{{ __("Add New Group") }}</h4>
            <form action="{{ route('admin.users.group.save') }}" method="POST" class="form-validate is-alter">
                <div class="row gy-4">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label" for="group-label">{{ __("Group Label") }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="group_label" class="form-control" id="group-label" placeholder="{{ __("Enter group label") }}" maxlength="190">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="group-color">{{ __("Group Color") }}</label>
                            <select name="group_color" id="group-color" class="form-control form-select form-select-sm" data-placeholder="{{ __("Please select") }}">
                                <option></option>
                                <option value="blue">{{ __('Blue') }}</option>
                                <option value="dark">{{ __('Black') }}</option>
                                <option value="gray">{{ __('Gray') }}</option>
                                <option value="indigo">{{ __('Indigo') }}</option>
                                <option value="purple">{{ __('Purple') }}</option>
                                <option value="pink">{{ __('Pink') }}</option>
                                <option value="orange">{{ __('Orange') }}</option>
                                <option value="teal">{{ __('Teal') }}</option>
                                <option value="success">{{ __('Green') }}</option>
                                <option value="danger">{{ __('Red') }}</option>
                                <option value="warning">{{ __('Yellow') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="group-desc">{{ __("Description") }}</label>
                            <div class="form-control-wrap">
                                <textarea name="group_desc" id="group-desc" class="form-control form-control-lg" placeholder="{{ __("Enter group description") }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                            <li>
                                <button type="button" class="btn btn-lg btn-primary group-add">{{ __("Add Group") }}</button>
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
	$(document).on('click', '.group-add', function() {
		let $self = $(this), $form = $self.parents("form"), url = $form.attr("action"), data = $form.serialize();
        if(url) {
            NioApp.Form.toPost(url, data, { btn: $self });
        }
	});
</script>
