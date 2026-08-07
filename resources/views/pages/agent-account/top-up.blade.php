@extends('layouts.iframe')

@section('content')
@include('pages.agent-account.partials.top-up-form', [
    'agentAccount' => $agentAccount,
    'embed' => true,
    'amount' => $amount ?? null,
    'method' => $method ?? null,
])
@endsection
