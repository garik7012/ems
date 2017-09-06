<script src="/js/plugins/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        if ( $('#date_born').prop('type') != 'date' ) {
            $('#date_born').datepicker({
                changeYear: true,
                yearRange: "1917:2017"
            });
        }
    })
</script>