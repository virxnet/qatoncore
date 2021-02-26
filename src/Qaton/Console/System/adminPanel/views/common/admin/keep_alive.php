<script type="text/javascript">
var keepAliveCnf = {};
keepAliveCnf.displayUpdateInterval = 1000;
keepAliveCnf.refreshInterval = 60000;
  
function keepAliveUpdate() {
    var count = $('#keepAliveDisplay .keep_alive_timer').text();
    var limit = <?php echo @ini_get("session.gc_maxlifetime"); ?>.0;
    count++;
    $('#keepAliveDisplay .keep_alive_timer').text(count);
    if (count >= (limit/2)) {
        $('#keepAliveDisplay').removeClass('badge-success').addClass('badge-danger');
    }
    if (count >= limit) {
        $('#keepAliveEnding').modal('show');
        clearInterval(keepAliveCnf.displayUpdateIntervalID);
        clearInterval(keepAliveCnf.refreshIntervalID);
    }
}

function keepAlive() {
    var randNum=Math.floor(Math.random()*11)
    var url = "<?php $this->baseUrl() ?>admin/panel/keep_alive?r=" + randNum;
    var keep_alive = $('input[name=keep_alive]:checked').val()
    if (keep_alive == 1) {
        console.log('Keep Alive ON...')
        $.ajax({
            url: url,
            context: document.body,
            contentType: "application/json",
            dataType: 'json',
            headers: { 'X-Qaton-Debug': 'false' }
        }).done(function(data) {
            $('#keepAliveDisplay .keep_alive_timer').text(0);
            $('#keepAliveDisplay').removeClass('badge-warning badge-danger text-dark').addClass('badge-success');
            console.log(data);
        }).fail(function() {
            $('#keepAliveDisplay').removeClass('badge-success').addClass('badge-warning text-dark');
        });
    } else {
        console.log('Keep Alive OFF...');
    }
    
}

$(document).ready(function() {
    keepAliveCnf.displayUpdateIntervalID = setInterval("keepAliveUpdate()", keepAliveCnf.displayUpdateInterval);
    keepAliveCnf.refreshIntervalID = setInterval("keepAlive()", keepAliveCnf.refreshInterval);
});
</script>