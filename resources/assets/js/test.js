connect(document,'onreadystatechange',function(e){
    console.log(document.readyState);
    if (document.readyState === 'complete')
    {
        alert("document.readyState === 'complete'");
        //dom is ready, window.onload fires later
    }
});
// window.onload = once a web page has completely loaded all content (including images, script files, CSS files, etc.).
connect(window, 'onload',
    function() {
        alert("window.onload");

        /*
            Find all DIVs tagged with the draggable class, and connect them to
            the Drag handler.
        */
        var form = getElement(['postform']);
        var d = getElementsByTagAndClassName('DIV', 'draggable');
        forEach(d,
            function(elem) {
                connect(elem, 'onmousedown', Drag.start);
            });

    });

// connect('postform', 'onclick', function(e) {  debugger; e.stop(); });
