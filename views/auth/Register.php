<div class="admin-container">
    <h2>Inscription</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <form method="POST" action="index.php?page=register">
        <label>Matricule:</label>
        <input type="text" name="matricule" required>
        <label>Nom:</label>
        <input type="text" name="nom" required>
        <label>Prénom:</label>
        <input type="text" name="prenom" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Téléphone:</label>
        <input type="text" name="telephone">
        <label>Formation:</label>
        <input type="text" name="formation" required>
        <label>Mot de passe:</label>
        <input type="password" name="password" required>
        <label>Confirmer le mot de passe:</label>
        <input type="password" name="confirm_password" required>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="index.php?page=login">Se connecter</a></p>
</div>
