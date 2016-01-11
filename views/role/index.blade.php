@extends('backend.layouts.main')
@section('content')
<div class="panel panel-default panel-nestable panel-sidebar">
    <div class="panel-heading clearfix">
        <div class="loading hidden"></div>
        <a href="{{route('backend.role.create')}}"
           class="modal-link btn btn-success btn-xs"
           data-title="{{trans('common.create_object', ['name' => trans('access-control::role.role')])}}"
           data-label="{{trans('common.save')}}"
           data-width="small"
           data-icon="align-justify">
            <span class="glyphicon glyphicon-plus-sign"></span> {{trans('access-control::role.create_role')}}
        </a>
    </div>
    <div class="panel-body">
        <div class="row row-height">
            <div class="row-height-inside">
                <div class="col-xs-9 col-height">
                    <div class="panel-body-content left">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="min-width">#</th>
                                    <th>{{trans('access-control::role.full_name_th')}}</th>
                                    <th>{{trans('access-control::role.short_name_th')}}</th>
                                    <th class="min-width">{{trans('access-control::role.acronym_name_th')}}</th>
                                    <th class="min-width">{{trans('access-control::role.users_th')}}</th>
                                    <th class="min-width">{{trans('access-control::role.level_th')}}</th>
                                    <th class="min-width">{{trans('common.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $i => $role)
                                <tr>
                                    <th class="min-width">{{$i +1}}</th>
                                    <td>{{$role->full_name}}</td>
                                    <td>{{$role->short_name}}</td>
                                    <td class="min-width">{{$role->acronym_name}}</td>
                                    <td class="min-width text-right">{{$role->users()->count()}}</td>
                                    <td class="min-width text-right"><code>{{$role->level}}</code></td>
                                    <td class="min-width">{!! $role->present()->actions !!}</td>
                                </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-3 col-height panel-body-sidebar right">
                    <ul class="nav nav-tabs tabs-right">
                    @foreach($groups as $group)
                        <li{!! $current->id ==$group->id ? ' class="active"':'' !!}>
                            {!!$group->present()->name_block!!}
                        </li>
                    @endforeach
                        <li class="button">
                            <a href="{{route('backend.role_group.create')}}"
                               class="modal-link btn btn-success"
                               data-toggle="tooltip"
                               data-title="{{trans('common.create_object', ['name' => trans('access-control::role_group.role_group')])}}"
                               data-label="{{trans('common.save')}}"
                               data-width="small"
                               data-icon="fa-sitemap">
                                <i class="fa fa-plus"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <div class="panel-footer">
        <span class="glyphicon glyphicon-info-sign"></span> {!!trans('access-control::role.level_hint')!!}
    </div>
</div>
@stop

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $.fn.mbHelpers.reloadPage = function () {
            location.reload(true);
        };
        $('a.delete_group, a.delete_role').click(function (e) {
            e.preventDefault();
            var title = $(this).data('title'),
                item_title = $(this).data('item_title'),
                item_name = $(this).data('item_name'),
                url = $(this).attr('href');

            window.bootbox.confirm({
                message: "<div class=\"message-delete\"><div class=\"confirm\">{!!trans('access-control::role.delete_confirm')!!} "+item_name+":</div><div class=\"title\">" + item_title + "</div>",
                title: title + '?',
                buttons: {
                    cancel: {label: '{{trans("common.cancel")}}', className: "btn-default btn-white"},
                    confirm: {label: '{{trans("common.ok")}}', className: "btn-danger"}
                },
                callback: function (ok) {
                    if (ok) {
                        $.post(url, {_token: window.csrf_token, _method: 'delete'},
                            function (message) {
                                $.fn.mbHelpers.showMessage(message.type, message.content);
                                $.fn.mbHelpers.reloadPage();
                            }, 'json'
                        );
                    }
                }
            });
        });
    });
</script>
@stop