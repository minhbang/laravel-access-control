@extends('backend.layouts.modal')
@section('content')
    {!! Form::model($role,['url' => $url, 'method' => $method]) !!}
    <div class="form-group{{ $errors->has('full_name') ? ' has-error':'' }}">
        {!! Form::label('label', trans('access-control::role.full_name'), ['class' => 'control-label']) !!}
        {!! Form::text('full_name', null, ['class' => 'has-slug form-control','data-slug_target' => "#system-name"]) !!}
        @if($errors->has('full_name'))
            <p class="help-block">{{ $errors->first('full_name') }}</p>
        @endif
    </div>
    <div class="form-group{{ $errors->has('short_name') ? ' has-error':'' }}">
        {!! Form::label('short_name', trans('access-control::role.short_name'), ['class' => 'control-label']) !!}
        {!! Form::text('short_name', null, ['class' => 'form-control']) !!}
        @if($errors->has('short_name'))
            <p class="help-block">{{ $errors->first('short_name') }}</p>
        @endif
    </div>
    <div class="form-group{{ $errors->has('acronym_name') ? ' has-error':'' }}">
        {!! Form::label('acronym_name', trans('access-control::role.acronym_name'), ['class' => 'control-label']) !!}
        {!! Form::text('acronym_name', null, ['class' => 'form-control']) !!}
        @if($errors->has('acronym_name'))
            <p class="help-block">{{ $errors->first('acronym_name') }}</p>
        @endif
    </div>
    <div class="form-group{{ $errors->has('system_name') ? ' has-error':'' }}">
        {!! Form::label('system_name', trans('access-control::role.system_name'), ['class' => 'control-label']) !!}
        {!! Form::text('system_name', null, ['class' => 'form-control text-navy', 'id' => 'system-name']) !!}
        @if($errors->has('system_name'))
            <p class="help-block">{{ $errors->first('system_name') }}</p>
        @endif
    </div>
    <div class="form-group{{ $errors->has('level') ? ' has-error':'' }}">
        {!! Form::label('level', trans('access-control::role.level'), ['class' => 'control-label']) !!}
        {!! Form::text('level', null, ['class' => 'form-control text-navy', 'id' => 'system-name']) !!}
        @if($errors->has('level'))
            <p class="help-block">{{ $errors->first('level') }}</p>
        @endif
    </div>
    {!! Form::close() !!}
@stop