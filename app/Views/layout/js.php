<script>
    let base_url = '<?= base_url()  ?>',
        current_url = '<?= current_url()  ?>';

    // setting
    xsetting = {
        spinner: ` <div class="spinner-border spinner-border-sm button-spinner" role="status"></div>`
    }
</script>

<!-- bootsrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- jquery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- bootbox -->
<style type="text/css">
    /* bootbox clos-button fix */
    .bootbox-close-button {
        box-sizing: content-box;
        color: #000 !important;
        opacity: initial !important;
        border: 0;
        border-radius: 0.25rem;
        background: none;
        font-size: 24px !important;
        font-weight: bold;
        padding: 0;
        margin: 0;
        float: initial !important
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" integrity="sha512-RdSPYh1WA6BF0RhpisYJVYkOyTzK4HwofJ3Q7ivt/jkpW6Vc8AurL1R+4AUcvn9IwEKAPm/fk7qFZW3OuiUDeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?= $this->renderSection('js') ?>