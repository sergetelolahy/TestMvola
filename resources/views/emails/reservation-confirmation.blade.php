<!-- resources/views/emails/reservation-confirmation.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmation de réservation</title>
</head>
<body>
    <h2>Bonjour {{ $reservation->client->nom ?? 'Cher client' }},</h2>

    <p>Votre réservation a bien été enregistrée.</p>

    <h3>Détails :</h3>
    <ul>
        <li>Numéro : {{ $reservation->id }}</li>
        <li>Date de reservation: {{ $reservation->date_creation }}</li>
        <li>Date de sejour: {{$reservation->date_debut}} à {{$reservation->date_fin}}</li>
        <li>Chambre : {{ $reservation->chambre->numero ?? 'N/A' }}</li>
        <li>Prix : {{ $reservation->tarif_template }} Ar</li>
    </ul>

    <p>Merci de votre confiance !</p>
    <p>— L’équipe Hotel Serge Foulpoint</p>
</body>
</html>
