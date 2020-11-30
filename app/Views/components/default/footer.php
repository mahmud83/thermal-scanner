<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col">
            <div class="copyright text-center text-lg-left text-muted">
                <?= getenv('app.version') ? '<b>Aplikasi Thermal Scanner versi ' . getenv('app.version') . '</b> - ' : '' ?>
                Copyright &copy; <?= date('Y') ?> Museum Listrik dan Energi Baru
            </div>
        </div>
    </div>
</footer>
