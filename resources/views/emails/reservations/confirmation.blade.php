@component('mail::message')
# Confirmation de réservation

Cher(e) {{ $reservation->nom_client }},

Votre réservation a été confirmée avec succès.

**Détails de la réservation :**
- Référence : {{ $reservation->reference }}
- Départ : {{ $reservation->departure->route }}
- Date et heure : {{ $reservation->departure->scheduled_time->format('d/m/Y H:i') }}
- Nombre de places : {{ $reservation->nombre_places }}
- Numéros de siège : {{ implode(', ', json_decode($reservation->siege_numeros)) }}
- Prix total : {{ number_format($reservation->prix_total, 2) }} FCFA

@component('mail::button', ['url' => route('reservations.show', $reservation->id)])
Voir ma réservation
@endcomponent

Merci de votre confiance,<br>
{{ config('app.name') }}
@endcomponent
