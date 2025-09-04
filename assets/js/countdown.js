
jQuery(function ($) {
    if (!window.dlCountdownIds) {
        return;
    }

    $.each(window.dlCountdownIds, function (_, id) {
        const $el = $('#' + id);
        if ($el.length === 0) {
            return;
        }

        const expiry = new Date($el.data('expiry'));
        const label = $el.data('label') || dlContentExpiry.label;

        function updateCountdown() {

            const now = Date.now();
            const diff = (expiry - now) / 1000;

            if (diff <= 0) {
                location.reload();

                return;
            }

            const days = Math.floor(diff / 86400);
            const hours = Math.floor((diff % 86400) / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = parseInt(diff % 60);

            let html = '';
            if (days > 0) {
                html += days + ' ' + dlContentExpiry.daysText + ', ';
            }

            if (hours > 0) {
                html += hours + ' ' + dlContentExpiry.hoursText + ', ';
            }

            let separator = ', ';
            if (html == '') {
                separator = ' ' + dlContentExpiry.andText + ' ';
            }
            if (hours == 0 && minutes == 0) {
                html += seconds + ' ' + dlContentExpiry.secondsText;
            } else {
                html += minutes + ' ' + dlContentExpiry.minutesText + separator;
                html += seconds + ' ' + dlContentExpiry.secondsText;
            }

            $el.html("<strong>" + label + "</strong> " + html);
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
