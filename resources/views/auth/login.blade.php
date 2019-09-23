@extends('layouts.auth')

@section('content')
    <form action="{{ route('login') }}" id="loginform" method="post">
        @csrf
        <div class="form-group mb-3">
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                   placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="form-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}"
                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-12 mt-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
            </div>
            <!-- /.col -->
        </div>

        <p class="mb-1 mt-4">
            <a href="#" id="to-recover">I forgot my password</a>
        </p>
    </form>

    <form class="form-horizontal" method="post" id="recoverform" style="display: none"
          action="{{ route('password.email') }}">
        {{ csrf_field() }}

        @if (session('status'))
            <div class="alert alert-success m-t-10">
                {{ session('status') }}
            </div>
        @endif

        <div class="form-group ">
            <div class="col-xs-12">
                <h3>@lang('app.recoverPassword')</h3>
                <p class="text-muted">@lang('app.enterEmailInstruction')</p>
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
            <div class="col-xs-12">
                <input class="form-control" type="email" id="email" name="email" required=""
                       placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
                        type="submit">@lang('app.sendPasswordLink')</button>
            </div>
        </div>

        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center">
                <p><a href="{{ route('login') }}" class="text-primary m-l-5"><b>{{ __('Login') }}</b></a></p>
            </div>
        </div>
    </form>
@endsection
