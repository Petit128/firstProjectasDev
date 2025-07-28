<div class="container">
    <h2>Options</h2>
    <?php if (isset($message) && $message != '') { echo "<p class='message'>$message</p>"; } ?>
    <?php if (isset($error) && $error != '') { echo "<p class='error'>$error</p>"; } ?>
    <label>Mode Sombre:</label>
    <input type="checkbox" id="darkModeToggle">
</div>
