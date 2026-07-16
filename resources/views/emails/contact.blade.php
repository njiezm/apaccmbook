@extends('emails.layout')

@section('body')
<h1>Nouveau message de contact</h1>
<p><strong>De :</strong> {{ $senderName }} ({{ $senderEmail }})</p>
<p><strong>Sujet :</strong> {{ $subjectLine }}</p>

<hr class="divider">
<p style="white-space:pre-line;">{{ $messageBody }}</p>
<hr class="divider">

<p style="font-size:13px;color:#888;">Vous pouvez répondre directement à cet email pour recontacter {{ $senderName }}.</p>
@endsection
