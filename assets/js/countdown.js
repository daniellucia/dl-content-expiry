document.addEventListener("DOMContentLoaded", function () {
    if (!window.dlCountdownIds) { 
        return; 
    }
    
    window.dlCountdownIds.forEach(function (id) {
        var el = document.getElementById(id);

        if (!el) { 
            return; 
        }

        var expiry = parseInt(el.getAttribute("data-expiry")) * 1000;
        var expiredText = el.getAttribute("data-expired-text") || "Expired";
        var label = el.getAttribute("data-label") || "Time left:";
        function updateCountdown() {
            var now = Date.now();
            var diff = Math.floor((expiry - now) / 1000);
            if (diff <= 0) {
                el.innerHTML = expiredText;
                var postContent = el.nextElementSibling;
                if (postContent) postContent.style.display = "none";
                return;
            }
            var days = Math.floor(diff / 86400);
            var hours = Math.floor((diff % 86400) / 3600);
            var minutes = Math.floor((diff % 3600) / 60);
            var seconds = diff % 60;
            el.innerHTML = label + " " +
                days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        }

        updateCountdown();

        setInterval(updateCountdown, 1000);

    });
});

jQuery(function($) {
    if (!window.dlCountdownIds) {
        return;
    }

    $.each(window.dlCountdownIds, function(_, id) {
        var $el = $('#' + id);
        if ($el.length === 0) {
            return;
        }

        var expiry = parseInt($el.data('expiry')) * 1000;
        var expiredText = $el.data('expired-text') || dlContentExpiry.expiredText;
        var label = $el.data('label') || dlContentExpiry.label;

        function updateCountdown() {
            var now = Date.now();
            var diff = Math.floor((expiry - now) / 1000);
            
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
