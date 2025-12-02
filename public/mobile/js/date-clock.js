(function ($) {
    'use strict';

    function updateDate() {
        var newDate = new Date();
        var dateFormatter = new Intl.DateTimeFormat(navigator.language, {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        var shortFormatter = new Intl.DateTimeFormat(navigator.language, {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });

        $('#dashboardDate').html(dateFormatter.format(newDate));
        $('#dashboardDate2').html(shortFormatter.format(newDate));
    }

    function updateTime() {
        var newDate = new Date();
        var options = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };

        var timeString = new Intl.DateTimeFormat(navigator.language, options).format(newDate);

        var timeParts = timeString.split(':');
        var hours = timeParts[0];
        var minutes = timeParts[1];
        var seconds = timeParts[2];

        $("#hours").html(hours);
        $("#min").html(minutes);
        $("#sec").html(seconds);
    }

    $(window).on("load", function() {
        updateDate();
        updateTime();
        setInterval(updateTime, 1000);
    });

})(jQuery);
