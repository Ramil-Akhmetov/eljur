@if ($crud->hasAccess('create'))
    <a href="javascript:void(0)" onclick="createTeacher(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/clone') }}" class="dropdown-item" data-button-type="clone"><i class="me-2 text-primary la la-chalkboard-teacher"></i> Сделать преподавателем</a>
@endif

{{-- Button Javascript--}}
{{-- - used right away in AJAX operations (ex: List)--}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show)--}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
    if (typeof createTeacher != 'function') {
        $("[data-button-type=clone]").unbind('click');

        function createTeacher(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');

            swal({
                title: "{!! trans('backpack::base.warning') !!}",
                {{--text: "{!! trans('backpack::crud.delete_confirm') !!}",--}}
                text: "Вы уверены, что хотите сделать этого пользователя преподавателем?",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "{!! trans('backpack::crud.cancel') !!}",
                        value: null,
                        visible: true,
                        className: "bg-secondary",
                        closeModal: true,
                    },
                    delete: {
                        text: "Сделать",
                        value: true,
                        visible: true,
                        className: "bg-success",
                    },
                },
                dangerMode: true,
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: route,
                        type: 'POST',
                        success: function(result) {
                            // Show an alert with the result
                            new Noty({
                                type: "success",
                                text: "<strong>Преподаватель успешно создан</strong><br>"
                            }).show();

                            // Hide the modal, if any
                            $('.modal').modal('hide');

                            if (typeof crud !== 'undefined') {
                                crud.table.ajax.reload();
                            }
                        },
                        error: function(result) {
                            // Show an alert with the result
                            console.log(result);
                            new Noty({
                                type: "warning",
                                text: `<strong>Произошла ошибка</strong><br> ${result.message}`
                            }).show();
                        }
                    });
                }
            })
        }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
</script>
@if (!request()->ajax()) @endpush @endif

