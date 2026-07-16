@extends('emails.layout')

@section('body')
<h1>Nouvelle vente à valider</h1>
<p>Un client vient de déclarer un paiement. Merci de le vérifier puis de valider l'accès depuis le tableau de bord.</p>

<div class="highlight-box">
    <p><strong>e-Livre :</strong> {{ $purchase->ebook->title }}<br>
    <strong>Client :</strong> {{ $purchase->user->name }} ({{ $purchase->user->email }})<br>
    <strong>Méthode :</strong> {{ ucfirst($purchase->payment_method ?? '—') }}<br>
    <strong>Date :</strong> {{ $purchase->created_at->format('d/m/Y à H\hi') }}</p>
</div>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('admin.dashboard') }}" class="btn">Ouvrir le tableau de bord</a>
</div>
@endsection
