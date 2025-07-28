<div class="auth-container">
    <h2>Connexion</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>
    <form method="POST" action="index.php?page=login">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Mot de passe:</label>
        <input type="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas de compte ? <a href="index.php?page=register">S'inscrire</a></p>
</div>
