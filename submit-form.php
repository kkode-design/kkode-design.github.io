<?php
// Configurazione database
$host = 'localhost';
$dbname = 'auraexperience_database';
$user = 'root';
$pass = 'auraexperience';

try {
    // Connessione sicura con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controllo che la richiesta sia POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Sanitizzazione e validazione base
        $nome     = trim($_POST["nome"]);
        $cognome  = trim($_POST["cognome"]);
        $email    = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        $telefono = preg_replace('/[^0-9+]/', '', $_POST["telefono"]); // accetta solo numeri e +

        if ($nome && $cognome && $email && $telefono) {
            // Query sicura con prepared statement
            $stmt = $pdo->prepare("INSERT INTO utenti_interessati (nome, cognome, email, telefono) 
                                   VALUES (:nome, :cognome, :email, :telefono)");
            $stmt->execute([
                ':nome' => $nome,
                ':cognome' => $cognome,
                ':email' => $email,
                ':telefono' => $telefono
            ]);

            echo "✅ Registrazione avvenuta con successo!";
        } else {
            echo "❌ Dati non validi. Controlla i campi.";
        }
    }
} catch (PDOException $e) {
    // Log dell’errore (meglio su file di log invece che mostrarlo all’utente)
    error_log("Errore DB: " . $e->getMessage());
    echo "⚠️ Errore di sistema. Riprova più tardi.";
}
?>
