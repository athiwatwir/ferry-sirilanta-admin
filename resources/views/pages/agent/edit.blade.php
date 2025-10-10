@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('agent.update',['agent'=>$agent])" :backUrl="route('agent.index')">
        @method('put')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="name" :value="$agent->name" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="code" label="code" class="inp-eng-num" help="Only a-z and 0-9" :value="$agent->code" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="prefix" label="Ticket Prefix" class="inp-eng-num" help="Only a-z and 0-9" :value="$ticketSeq->prefix" />
                <input type="hidden" name="prefix_old" value="{{ $ticketSeq->prefix }}">
            </div>
            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        @if (!empty($agent->logo))
                        <img src="{{ asset($agent->logo) }}" class="w-100" />
                        @else
                        <img src="{{ asset('images/no-image.webp') }}" class="w-100" />
                        @endif
                    </div>
                    <div class="col-12 col-lg-9">
                        <label for="formFileLg" class="form-label">Upload Logo</label>
                        <input class="form-control" id="logo" name="logo" type="file" accept="image/*">
                    </div>
                </div>


            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea :value="$agent->description" />
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.switch name="is_use_wallet" label="Use Wallet" :value="$agent->is_use_wallet" />
            </div>

            <div class="col-12 col-lg-6">
                <x-form.switch name="is_use_api" label="Use API" :value="$agent->is_use_api" />
                <div class="row" id="box-api" @if ($agent->is_use_api =='N')
                    style="display: none;"
                    @endif >
                    <div class="col-12">
                        <x-form.float.input name="api_key" label="api key" :isrequire="false" value="{{ $agent->api_key }}" />
                    </div>
                </div>
            </div>
        </div>
    </x-form>
</x-card>

@stop

@section('script')
<script src="{{ asset('js/form-input.js') }}"></script>

<script>
    function generateApiKey(length = 50) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let apiKey = '';
        for (let i = 0; i < length; i++) {
            apiKey += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return apiKey;
    }

    $(document).ready(function() {
        $('#is_use_api').on('change', function() {
            if ($(this).is(':checked')) {
                $('#box-api').show();
                if ($('#api_key').val() == '') {
                    $('#api_key').val(generateApiKey());
                }

            } else {
                $('#box-api').hide();
            }
        });
    });

</script>
@stop
