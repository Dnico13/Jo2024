<?php
// On importe les fonctions à tester depuis le fichier auth.utils.php
require_once '../backend/api/auth.utils.php';

// On initialise un tableau qui va contenir tous les résultats des tests
$tests = [];

// === Tests de la fonction emailEtMotDePasseSontRemplis ===

// Cas où les deux champs sont bien remplis (doit renvoyer true)
$tests[] = [
  'fonction' => 'emailEtMotDePasseSontRemplis',
  'cas' => 'Champs remplis',
  'résultat' => emailEtMotDePasseSontRemplis("a@a.fr", "1234")
];

// Cas où l'email est vide (doit renvoyer false)
$tests[] = [
  'fonction' => 'emailEtMotDePasseSontRemplis',
  'cas' => 'Email vide',
  'résultat' => !emailEtMotDePasseSontRemplis("", "1234")
];

// Cas où le mot de passe est vide (doit renvoyer false)
$tests[] = [
  'fonction' => 'emailEtMotDePasseSontRemplis',
  'cas' => 'Mot de passe vide',
  'résultat' => !emailEtMotDePasseSontRemplis("a@a.fr", "")
];

// === Tests de la fonction motDePasseEstValide ===

// On génère un hash pour simuler un mot de passe sécurisé
$hash = password_hash("secret", PASSWORD_BCRYPT);

// Test avec le bon mot de passe (doit renvoyer true)
$tests[] = [
  'fonction' => 'motDePasseEstValide',
  'cas' => 'Mot de passe correct',
  'résultat' => motDePasseEstValide("secret", $hash)
];

// Test avec un mot de passe incorrect (doit renvoyer false)
$tests[] = [
  'fonction' => 'motDePasseEstValide',
  'cas' => 'Mot de passe incorrect',
  'résultat' => !motDePasseEstValide("mauvais", $hash)
];

// === Tests de la fonction codeEstValide ===

// On récupère l'heure actuelle
$now = time();

// Code correct et encore valide (date dans le futur)
$tests[] = [
  'fonction' => 'codeEstValide',
  'cas' => 'Code correct et non expiré',
  'résultat' => codeEstValide("123456", "123456", $now + 60)
];

// Code faux (ne correspond pas à celui attendu)
$tests[] = [
  'fonction' => 'codeEstValide',
  'cas' => 'Code incorrect',
  'résultat' => !codeEstValide("000000", "123456", $now + 60)
];

// Code correct mais expiré (date dans le passé)
$tests[] = [
  'fonction' => 'codeEstValide',
  'cas' => 'Code expiré',
  'résultat' => !codeEstValide("123456", "123456", $now - 10)
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tests Unitaires PHP</title>
  <style>
    body { font-family: sans-serif; padding: 2rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { padding: 0.7rem 1rem; border: 1px solid #ccc; text-align: left; }
    th { background: #f0f0f0; }
    .success { background-color: #e6ffe6; }
    .fail { background-color: #ffeaea; }
    .count { margin-top: 1rem; font-weight: bold; }
  </style>
</head>
<body>
  <h1>Résultats des tests unitaires PHP</h1>
  <table>
    <thead>
      <tr>
        <th>Fonction</th>
        <th>Cas testé</th>
        <th>Résultat</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $ok = 0;
      $ko = 0;
      foreach ($tests as $test):
        $test['résultat'] ? $ok++ : $ko++;
        ?>
        <tr class="<?= $test['résultat'] ? 'success' : 'fail' ?>">
          <td><?= htmlspecialchars($test['fonction']) ?></td>
          <td><?= htmlspecialchars($test['cas']) ?></td>
          <td><?= $test['résultat'] ? 'OK' : 'Échec' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="count">
    <?= $ok ?> test(s) réussi(s), <?= $ko ?> échec(s)
  </div>
</body>
</html>