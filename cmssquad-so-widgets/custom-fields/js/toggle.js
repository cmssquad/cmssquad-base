(function ($) {
    $(document).on('sowsetupformfield', '.siteorigin-widget-field-type-csow-toggle', function (e) {

        $('input.switchery').each(function () {
            var $this = $(this);

            // to prevent switchery init again on multiple checkbox existences
            if( typeof $this.data('switchery') === "undefined" ) {
                var switchery = new Switchery($this[0], {
                    size: 'small',
                    color: '#267FAD'
                });
            }
        });
    });
})(jQuery);