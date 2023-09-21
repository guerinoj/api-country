<?php
require 'vendor/autoload.php'; // Importez l'autoloader Composer

use Predis\Client;

function getDataFromAPI()
{

    // Créez une instance du client Predis pour se connecter à Redis
    $client = new Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
    ]);

    // URL de l'API
    $api_url = "https://restcountries.com/v3.1/all";

    // Effectuer la requête HTTP pour récupérer les données JSON
    $response = file_get_contents($api_url);

    // Vérifier si la requête a réussi
    if ($response === false) {
        die("Erreur lors de la récupération des données de l'API.");
    }

    $client->set('countries', $response);

    // Convertir la réponse JSON en tableau associatif
    $data = json_decode($response, true);

    // Vérifier si la conversion a réussi
    if ($data === null) {
        die("Erreur lors de la conversion des données JSON.");
    }


    return $data;
}

function displayCountries($countries)
{
    // Afficher la liste des pays
    echo "<h1>Liste des pays :</h1>";
    echo "<ul>";
    foreach ($countries as $country) {
        echo "<li>" . $country['name']['common'] . "</li>";
    }
    echo "</ul>";
}

// Créez une instance du client Predis pour se connecter à Redis
$client = new Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
]);

if ($client->get('countries')) {
    $countries = json_decode($client->get('countries'), true);
} else {
    $countries = getDataFromAPI();
}


displayCountries($countries);
