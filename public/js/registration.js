var ws;
$(document).ready(function() {

    // test if the browser supports web sockets
    if ("WebSocket" in window) {
        connect("ws://127.0.0.1:21187/fps");
    } else {
        $('#state').val('Error: Browser not supported!');
    };

    // function to send data on the web socket
    function ws_send(str) {
        try {
            ws.send(str);
        } catch (err) {
            $('#state').val('Error!');
        }
    }

    function connect(host) {

        try {
            ws = new WebSocket(host);
        } catch (err) {
            $('#state').val('Error!');
        }

        ws.onopen = function() {
            $('#state').val('Ready!');
        };

        ws.onmessage = function(evt) {
            var obj = eval("(" + evt.data + ")");
            var status = document.getElementById("state");
            switch (obj.Workmsg) {
                case 1:
                    status.value = "Please connect the device";
                    break;
                case 2:
                    status.value = "Place Finger";
                    break;
                case 3:
                    status.value = "Lift Finger";
                    break;
                case 4:
                    //status.value = "";
                    break;
                case 5:
                    if (obj.Restmsg == 1) {
                        status.value = "Capture succeeded";
                        if (obj.Data1 != "null") {
                            var en2 = document.getElementById("e2");
                        @this.template1 = obj.Data1;
                        }
                    } else {
                        status.value = "Capture Fail";
                    }
                    break;
                case 6:
                    if (obj.Restmsg == 1) {
                        status.value = "Enrol succeeded";
                        if (obj.Data1 != "null") {
                        @this.template = obj.Data1;
                        }
                    } else {
                        status.value = "Enrol Fail";
                    }
                    break;
                case 7:
                    if (obj.Image) {
                        if (obj.Image == "null" || obj.Image == null) break;
                        let img = document.getElementById("imgDiv");
                        img.src = "data:image/png;base64," + obj.Image;
                    @this.image = obj.Image;
                    }
                    break;
                case 8:
                    status.value = "Time Out"
                    break;
                case 9:
                    status.value = "Match Result:" + obj.Restmsg;
                    break;
                case 10:
                    if (obj.Image != "null") {
                        var en4 = document.getElementById("e4");
                        en4.value = obj.Image;
                    }
                    break;
                case 15:
                    if (obj.retmsg == 1) {
                        status.value = "Reconnect device succeeded";
                    } else {
                        status.value = "Reconnect device fail";
                    }
                    break;
            }
        };

        ws.onclose = function() {
            $('#state').val('Error:Close!');
            $('#host_connect').attr('disabled', false);
        };
    };

    function debug(msg, type) {
        $("#console").append('<p class="' + (type || '') + '">' + msg + '</p>');
    };

    $('#host_connect').click(function() {
        $('#console').html('');
        connect("ws://127.0.0.1:21187/fps");
    });

    $('#host_close').click(function() {
        $('#console').html('');
        ws.close();
    });


    $('#EnrollTemplate').click(function() {
    @this.image = null;
    @this.template = null;

        EnrollTemplate();
    })

    $('#GetTemplate').click(function() {
        GetTemplate();
    })

    $('#MatchTemplate').click(function() {
        MatchTemplate();
    })

});

function EnrollTemplate() {
    try {
        let cmd = "{\"cmd\":\"enrol\",\"data1\":\"\",\"data2\":\"\"}";
        ws.send(cmd);
    } catch (err) {}
    document.getElementById("state").value = "Place Finger";
}

function GetTemplate() {
    try {
        let cmd = "{\"cmd\":\"capture\",\"data1\":\"\",\"data2\":\"\"}";
        ws.send(cmd);
    } catch (err) {}
    document.getElementById("state").value = "Place Finger";
}

function MatchTemplate() {
    var v1 = @this.template;
    var v2 = @this.template1;
    var cmd = "{\"cmd\":\"match\",\"data1\":\"" + v1 + "\",\"data2\":\"" + v2 + "\"}";
    try {
        ws.send(cmd);
        $("#console").append('<p class="response">' + cmd + '</p>');

    } catch (err) {}
}