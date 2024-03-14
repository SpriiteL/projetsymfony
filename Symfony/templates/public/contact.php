<?php

if (isset($_POST['envoyer'])) {
  
  if (empty($_POST['nom'])) {
    echo "<p>Le champ Nom est vide.</p>";
  } elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
    echo "<p>L'adresse email entrée est incorrecte.</p>";
  } elseif (empty($_POST['message'])) {
    echo "<p>Le champ message est vide.</p>";
  } else {
    
    $votre_adresse_mail = 'nathanpa.etude@gmail.com';
    
    $mail_de_lutilisateur = $_POST['mail'];
    
    $entetes_du_mail = [];
    $entetes_du_mail[] = 'MIME-Version: 1.0';
    $entetes_du_mail[] = 'Content-type: text/html; charset=UTF-8';
    $entetes_du_mail[] = 'From: Nom de votre site <' . $mail_de_lutilisateur . '>';
    $entetes_du_mail[] = 'Reply-To: Nom de votre site <' . $mail_de_lutilisateur . '>';
    
    $entetes_du_mail = implode("\r\n", $entetes_du_mail);
    
    $sujet = 'Nouveau message depuis votre site';
    
    $message = nl2br(htmlspecialchars($_POST['message']));
    
    if (mail($votre_adresse_mail, $sujet, $message, $entetes_du_mail)) {
      echo "<p>Le mail a été envoyé avec succès !</p>";
    } else {
      echo "<p>Une erreur est survenue, le mail n'a pas été envoyé.</p>";
    }
  }
}
?>
