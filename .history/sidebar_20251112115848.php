<!-- sidebar.php -->
<div class="sidebar">
    <h2>🚍 ONG Transport</h2>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">🏠 Tableau de bord</a>
    <a href="ajouter_eleve.php" class="<?= basename($_SERVER['PHP_SELF']) == 'ajouter_eleve.php' ? 'active' : '' ?>">👩‍🎓 Ajouter Élève</a>
    <a href="gerer_abonnement.php" class="<?= basename($_SERVER['PHP_SELF']) == 'gerer_abonnement.php' ? 'active' : '' ?>">💳 Abonnements</a>
    <a href="transport.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transport.php' ? 'active' : '' ?>">🚌 Bus & Chauffeurs</a>
    <a href="rechercher.php" class="<?= basename($_SERVER['PHP_SELF']) == 'rechercher.php' ? 'active' : '' ?>">🔍 Rechercher</a>
</div>
