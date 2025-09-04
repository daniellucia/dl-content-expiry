
jQuery(function($) {
    if (!window.dlCountdownIds) {
        return;
    }

    $.each(window.dlCountdownIds, function(_, id) {
        const $el = $('#' + id);
        if ($el.length === 0) {
            return;
        }

        const expiry = new Date($el.data('expiry'));
        const expiredText = $el.data('expired-text') || dlContentExpiry.expiredText;
        const label = $el.data('label') || dlContentExpiry.label;

        function updateCountdown() {
            
            const now = new Date($el.data('now'));
            const diff = (expiry - now) / 1000;

            if (diff <= 0) {
                $el.html(expiredText);
                const $postContent = $el.next();
                if ($postContent.length) $postContent.hide();
                return;
            }

            const days = Math.floor(diff / 86400);
            const hours = Math.floor((diff % 86400) / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;

            let html = '';
            if (days > 0) {
                html += days + ' ' + dlContentExpiry.daysText + ', ';
            }

            if (hours > 0) {
                html += hours + ' ' + dlContentExpiry.hoursText + ', ';
            }

            html += minutes + ' ' + dlContentExpiry.minutesText + ', ';
            html += seconds + ' ' + dlContentExpiry.secondsText;

            $el.html("<strong>" + label + "</strong> " + html);
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
