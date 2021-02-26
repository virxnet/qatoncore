<script type="text/javascript">
window.onload = function() {
    window.setInterval("keepAliveUpdate()", 1000);
    window.setInterval("keepAlive()", 9000);
};
function keepAliveUpdate() {
    var count = $('#keepAliveDisplay').text();
    count++;
    $('#keepAliveDisplay').text(count);
    if (count >= 6) {
        $('#keepAliveDisplay').removeClass('badge-success').addClass('badge-danger');
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
            $('#keepAliveDisplay').text(0);
            $('#keepAliveDisplay').removeClass('badge-danger').addClass('badge-success');
            console.log(data);
        }).fail(function() {
            $('#keepAliveDisplay').removeClass('badge-success').addClass('badge-danger');
        });
    } else {
        console.log('Keep Alive OFF...');
    }
    
}
</script>