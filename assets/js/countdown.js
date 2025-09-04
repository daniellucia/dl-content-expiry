
jQuery(function($) {
    if (!window.dlCountdownIds) {
        return;
    }

    $.each(window.dlCountdownIds, function(_, id) {
        var $el = $('#' + id);
        if ($el.length === 0) {
            return;
        }

        var expiry = new Date($el.data('expiry'));
        var expiredText = $el.data('expired-text') || dlContentExpiry.expiredText;
        var label = $el.data('label') || dlContentExpiry.label;

        function updateCountdown() {
            
            var now = new Date($el.data('now'));
            var diff = (expiry - now) / 1000;

            if (diff <= 0) {
                $el.html(expiredText);
                var $postContent = $el.next();
                if ($postContent.length) $postContent.hide();
                return;
            }

            var days = Math.floor(diff / 86400);
            var hours = Math.floor((diff % 86400) / 3600);
            var minutes = Math.floor((diff % 3600) / 60);
            var seconds = diff % 60;

            html = '';
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
