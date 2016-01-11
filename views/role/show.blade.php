@extends('backend.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="ibox ibox-table">
                <div class="ibox-title">
                    <h5>{!! trans('access-control::role.info') !!}</h5>
                    <div class="ibox-tools">
                        {!! $role->present()->link_edit !!}
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover table-striped table-bordered table-detail">
                        <tbody>
                            <tr>
                                <td>ID</td>
                                <td><strong>{{$role->id}}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ trans('access-control::role.full_name') }}</td>
                                <td><strong>{{$role->full_name}}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ trans('access-control::role.short_name') }}</td>
                                <td><strong>{{$role->short_name}}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ trans('access-control::role.acronym_name') }}</td>
                                <td><strong>{{$role->acronym_name}}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ trans('access-control::role.system_name') }}</td>
                                <td><strong>{{$role->system_name}}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ trans('access-control::role.level') }}</td>
                                <td><code>{{$role->level}}</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox ibox-table">
                <div class="ibox-title">
                    <h5>{!! trans('access-control::role.attached_users') !!}</h5>
                    <div class="ibox-tools">
                        <a id="detach-all-user" href="{{route('backend.role.user.detach_all', ['role' => $role->id])}}" class="btn btn-danger btn-xs">
                            <span class="fa fa-remove"></span> {{trans('access-control::role.detach_all')}}
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal form-1-line">
                        <div class="form-group">
                            {!! Form::label('user_id', trans('access-control::role.add_user'), ['class' => 'col-xs-3 control-label']) !!}
                            <div class="col-xs-9">
                                {!! Form::select('user_id', [], null, ['id' => 'user_id', 'class' => 'form-control select-user', 'placeholder' => trans('user::user.select_user').'...']) !!}
                                <a id="attach-user" href="{{route('backend.role.user.attach', ['role' => $role->id, 'user' => '__ID__'])}}" class="btn btn-primary btn-block disabled"><i class="fa fa-plus"></i> {{trans('access-control::role.attach')}}</a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-hover table-striped table-bordered table-detail table-users">
                        <thead>
                            <tr>
                                <th class="min-width">#</th>
                                <th>{{trans('user::user.name')}}</th>
                                <th class="min-width">{{trans('user::user.username_th')}}</th>
                                <th class="min-width">{{trans('user::user.group_id')}}</th>
                                <th class="min-width"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $i => $user)
                            <tr>
                                <td class="min-width">{{$i+1}}</td>
                                <td>{{$user->name}}</td>
                                <td class="min-width">{{$user->username}}</td>
                                <td class="min-width">{{$user->group->acronym_name}}</td>
                                <td class="min-width">
                                    <a href="{{route('backend.role.user.detach', ['role' => $role->id, 'user' => $user->id])}}"
                                    class="detach-user text-danger"
                                    data-toggle="tooltip"
                                    data-title="{{trans('access-control::role.detach')}}">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>{!! trans('access-control::permission.permissions') !!}</h5>
            <div class="ibox-tools">
                <a id="sync-permission"
                   href="{{route('backend.permission.sync')}}"
                   data-toggle="tooltip"
                   data-title="{{trans('access-control::permission.sync_resource_actions')}}"
                   class="btn btn-success btn-xs">
                    <span class="fa fa-refresh"></span> {{trans('access-control::permission.sync')}}
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-6">
                    <div class="panel panel-default panel-no-padding">
                        <div class="panel-heading">{{trans('access-control::permission.not_attached')}}</div>
                        <div class="panel-body">
                            <table class="table table-hover table-bordered table-permissions">
                                <tbody>
                                <?php $count = 1 ?>
                                @foreach($other_permissions as $resource => $actions)
                                    <tr>
                                        <td class="min-width">{{$count++}}</td>
                                        <td>
                                            {!! $actions[0]->present()->resource !!}
                                            <div class="actions">
                                                @foreach($actions as $i => $permission)
                                                    <div class="checkbox checkbox-success">
                                                        <input type="checkbox" id="action-{{$permission->id}}" data-id="{{$permission->id}}"/>
                                                        <label for="action-{{$permission->id}}">{{$permission->present()->action}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="min-width">
                                            <a href="{{route('backend.role.permission.attach', ['role' => $role->id, 'ids' => '__IDS__'])}}"
                                               class="attach-permission text-success"
                                               data-toggle="tooltip"
                                               data-title="{{trans('access-control::permission.attach')}}">
                                                <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="panel panel-green panel-no-padding">
                        <div class="panel-heading">
                            {{trans('access-control::permission.attached')}}
                            <div class="pull-right">
                                <a id="detach-all-permission" href="{{route('backend.role.permission.detach_all', ['role' => $role->id])}}" class="btn btn-danger btn-xs">
                                    <span class="fa fa-remove"></span> {{trans('access-control::role.detach_all')}}
                                </a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover table-bordered table-permissions">
                                <tbody>
                                <?php $count = 1 ?>
                                @foreach($permissions as $resource => $actions)
                                    <tr>
                                        <td class="min-width">{{$count++}}</td>
                                        <td>
                                            {!! $actions[0]->present()->resource !!}
                                            <div class="actions">
                                                @foreach($actions as $i => $permission)
                                                    <div class="checkbox checkbox-success">
                                                        <input type="checkbox" id="action-{{$permission->id}}" data-id="{{$permission->id}}"/>
                                                        <label for="action-{{$permission->id}}">{{$permission->present()->action}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="min-width">
                                            <a href="{{route('backend.role.permission.detach', ['role' => $role->id, 'ids' => '__IDS__'])}}"
                                               class="detach-permission text-danger"
                                               data-toggle="tooltip"
                                               data-title="{{trans('access-control::permission.detach')}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $.fn.mbHelpers.reloadPage = function () {
                location.reload(true);
            };
            var user_id = $('#user_id'),
                attach_user = $('#attach-user');
            user_id.selectize_user({
                url: '{!! route('backend.user.select', ['query' => '__QUERY__']) !!}',
                users: {!! json_encode($selectize_users) !!},
                onChange: function(value){
                    if(value){
                        attach_user.removeClass('disabled');
                    } else{
                        attach_user.addClass('disabled');
                    }
                }
            });
            attach_user.click(function(e){
                e.preventDefault();
                var id = user_id.val(),
                    url = attach_user.attr('href');
                if(attach_user.hasClass('disabled') || id <= 0){
                    return;
                }
                $.post(url.replace('__ID__', id), {_token: window.csrf_token}, function (data) {
                    $.fn.mbHelpers.showMessage(data.type, data.content);
                    $.fn.mbHelpers.reloadPage();
                }, 'json');
            });

            function detach_action(element, message, title, ids){
                var _this = $(element);
                ids = ids || '';
                _this.tooltip('hide');
                window.bootbox.confirm({
                    message: '<div class="message-delete"><div class="confirm">' + message + '</div></div>',
                    title: '<i class="fa fa-remove"></i> ' + title,
                    buttons: {
                        cancel: {label: '{{trans("common.cancel")}}', className: "btn-default btn-white"},
                        confirm: {label:  '{{trans("common.ok")}}', className: "btn-danger"}
                    },
                    callback: function (ok) {
                        if (ok) {
                            $.post(_this.attr('href').replace('__IDS__', ids), {_token: csrf_token, _method: 'delete'}, function (data) {
                                $.fn.mbHelpers.showMessage(data.type, data.content);
                                if(ids.length <= 0){
                                    _this.parents('tr').remove();
                                }
                                $.fn.mbHelpers.reloadPage();
                            }, 'json');
                        }
                    }
                });
            }

            $('a.detach-user').click(function(e){
                e.preventDefault();
                detach_action(
                    this,
                    '{{trans("access-control::role.detach_user_confirm")}}',
                    '{{trans("access-control::role.detach_user")}}'
                );
            });

            $('#detach-all-user').click(function(e){
                e.preventDefault();
                detach_action(
                    this,
                    '{{trans("access-control::role.detach_all_user_confirm")}}',
                    '{{trans("access-control::role.detach_all")}}'
                );
            });

            $('#sync-permission').click(function(e){
                e.preventDefault();
                var _this = $(this);
                _this.tooltip('hide');
                $.post(_this.attr('href'), {_token: csrf_token}, function (data) {
                    $.fn.mbHelpers.showMessage(data.type, data.content);
                    $.fn.mbHelpers.reloadPage();
                }, 'json');
            });

            $('.attach-permission').click(function(e){
                e.preventDefault();
                var _this = $(this),
                    ids = [];
                _this.tooltip('hide');
                _this.parents('tr').find('input[type="checkbox"]:checked').each(function(){
                    ids.push($(this).data('id'));
                });
                if(ids.length){
                    $.post(_this.attr('href').replace('__IDS__', ids.join(',')), {_token: window.csrf_token}, function (data) {
                        $.fn.mbHelpers.showMessage(data.type, data.content);
                        $.fn.mbHelpers.reloadPage();
                    }, 'json');
                }
            });

            $('a.detach-permission').click(function(e){
                e.preventDefault();
                var _this = $(this),
                    ids = [];
                _this.parents('tr').find('input[type="checkbox"]:checked').each(function(){
                    ids.push($(this).data('id'));
                });
                if(ids.length) {
                    detach_action(
                        this,
                        '{{trans("access-control::role.detach_permission_confirm")}}',
                        '{{trans("access-control::role.detach_permission")}}',
                        ids.join(',')
                    );
                } else{
                    _this.tooltip('hide');
                }
            });

            $('#detach-all-permission').click(function(e){
                e.preventDefault();
                detach_action(
                    this,
                    '{{trans("access-control::role.detach_all_permission_confirm")}}',
                    '{{trans("access-control::role.detach_all")}}'
                );
            });
        });
    </script>
@stop