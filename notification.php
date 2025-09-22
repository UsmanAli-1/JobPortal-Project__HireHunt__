<?php
$bgColor = '#f44336'; // default red (fail)
if (isset($_SESSION['success'])) {
    $bgColor = '#2ECE2E'; // green (success)
}
?>
<?php if (isset($_SESSION['success']) || isset($_SESSION['fail'])): ?>
<div id="notification" style="
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 10px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    font-size: 1rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    background-color: <?= $bgColor ?>;
    max-width: 90%;
    width: fit-content;
    text-align: center;
    word-wrap: break-word;
">
    <?php
    if (isset($_SESSION['success'])) {
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    } else if (isset($_SESSION['fail'])) {
        echo $_SESSION['fail'];
        unset($_SESSION['fail']);
    }
    ?>
</div>

<style>
@media (max-width: 480px) {
    #notification {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
}
</style>

<script>
setTimeout(function () {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.transition = "opacity 0.5s ease";
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }
}, 1000);
</script>
<?php endif; ?>
