@extends('backend.layouts.modal')
@section('content')
    {!! Form::model($role_group,['url' => $url, 'method' => $method]) !!}
    <div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
        {!! Form::label('label', trans('access-control::role_group.name'), ['class' => 'control-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        @if($errors->has('name'))
            <p class="help-block">{{ $errors->first('name') }}</p>
        @endif
    </div>
    {!! Form::close() !!}
@stop