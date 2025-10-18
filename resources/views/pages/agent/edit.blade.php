@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('agent.update',['agent'=>$agent])" :backUrl="route('agent.index')">
        @method('put')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="name" :value="$agent->name" />
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
