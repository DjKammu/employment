<h2>Sign document</h2>

 <iframe src="<?php echo $signlink ?>" frameborder="0" height="900" width="1700"></iframe>

<script>
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

    // Listen to message from child window
    eventer(messageEvent, function(e) {
        console.log('parent received message!:  ', e.data);

        if (e.data === "signcompleted") {
            window.location.href = "/signcompleted"
        }
    }, false);
</script>
