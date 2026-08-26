    <script>
        window.K3RS_DATA = <?= json_encode(isset($bootstrap) ? $bootstrap : array(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        window.K3RS_API_URL = <?= json_encode(base_url('index.php/api/')) ?>;
        window.K3RS_HOME_URL = <?= json_encode(base_url('index.php/dashboard')) ?>;
    </script>
    <script src="<?= base_url('assets/js/k3rs.js') ?>"></script>
</body>
</html>
