<!-- sidebar.php -->
 <style>
    .sidebar {
    height: 100vh;          /* hauteur de l’écran */
    overflow-y: auto;       /* scroll vertical */
    overflow-x: hidden;     /* pas de scroll horizontal */
    }

 </style>
<div class="sidebar">
    <h2><img src="images/LOGO.png" alt="">Christian Ndangi fondation</h2>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">🏠 Tableau de bord</a>
    <a href="ajouter_eleve.php" class="<?= basename($_SERVER['PHP_SELF']) == 'ajouter_eleve.php' ? 'active' : '' ?>">👩‍🎓 Ajouter Élève</a>
    <a href="liste_eleves.php" class="<?= basename($_SERVER['PHP_SELF']) == 'liste_eleves.php' ? 'active' : '' ?>">📋 Liste des élèves</a>
    <a href="gerer_abonnement.php" class="<?= basename($_SERVER['PHP_SELF']) == 'gerer_abonnement.php' ? 'active' : '' ?>">💳 Abonnements</a>
    <a href="dette.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dette.php' ? 'active' : '' ?>">💰 Dettes</a>
        <a href="rechercher.php" class="<?= basename($_SERVER['PHP_SELF']) == 'rechercher.php' ? 'active' : '' ?>">🔍 Rechercher</a>
    <a href="recette_mensuel.php" class="<?= basename($_SERVER['PHP_SELF']) == 'recette_mensuel.php' ? 'active' : '' ?>">📅 Recettes Mensuelles</a>
    <a href="transport.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transport.php' ? 'active' : '' ?>">🚌 Bus & Chauffeurs</a>

</div>
