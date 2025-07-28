<div class="container">
    <h2>Notifications</h2>
    <?php
    if (isset($message) && $message != '') {
        echo "<p class='message'>" . htmlspecialchars($message) . "</p>";
    }
    if (isset($error) && $error != '') {
        echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
    }
    ?>

    <?php
    if (isset($user_notifications) && is_array($user_notifications) && count($user_notifications) > 0) {
        foreach ($user_notifications as $notification) {
            echo "<div class='notification-item'>";
            echo "<p>" . htmlspecialchars($notification['message']) . "</p>";
            echo "<small>" . htmlspecialchars($notification['timestamp']) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucune notification pour le moment.</p>";
    }
    ?>
</div>
