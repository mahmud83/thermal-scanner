<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col">
            <div class="copyright text-center text-lg-left text-muted">
                <?= getenv('app.version') ? '<b>Aplikasi Absensi versi ' . getenv('app.version') . '</b> - ' : '' ?>
                Copyright &copy; <?= date('Y') ?>
                <a href="https://www.poltekkesjakarta3.ac.id/" class="font-weight-bold ml-1" target="_blank">POLTEKKES
                    Jakarta III</a>
            </div>
        </div>
    </div>
</footer>
