<div>
    <style type="text/css">
        #console_wrapper {
            background-color: black;
            color: white;
        }

        #console p {
            padding: 0;
            margin: 0;
            color: white
        }

        #console .success {
            color: green;
        }

        #console .error {
            color: red;
        }
    </style>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Enroll Finger</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">
                    <div class="col-md-12 mb-2">
                        <div id="console_wrapper" class="p-2 rounded">
                            <pre id="console"></pre>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2 d-flex justify-content-end">
                        <input type="submit" class="btn btn-outline-info btn-sm" name="host_connect" id="host_connect"
                            value="Reconnect!" /> &nbsp;
                        <input type="submit" class="btn btn-outline-danger btn-sm" name="host_close" id="host_close"
                            value="Close!" />
                    </div>

                    @if ($errors->any())
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif


                    <div class="col-md-12 mb-2">
                        <table width="100%" border="1" cellspacing="0">
                            <tr align="center">
                                <td width="30%">
                                    @if ($image)
                                        <img src="data:image/png;base64,{{ $image }}" alt=""
                                            width="256" height="288" id="imgDiv" align="middle" />
                                    @else
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAkCAYAAABIdFAMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNo8zjsOxCAMBFB/KEAUFFR0Cbng3nQPw68ArZdAlOZppPFIBhH5EAB8b+Tlt9MYQ6i1BuqFaq1CKSVcxZ2Acs6406KUgpt5/LCKuVgz5BDCSb13ZO99ZOdcZGvt4mJjzMVKqcha68iIePB86GAiOv8CDADlIUQBs7MD3wAAAABJRU5ErkJggg=="
                                            alt="" width="256" height="288" id="imgDiv"
                                            align="middle" />
                                    @endif

                                </td>
                                <td width="25%">
                                    <input type="button" value="Enrol Template" id="EnrollTemplate"
                                        class="btn btn-outline-info btn-sm">
                                </td>
                            </tr>
                            <tr align="center">
                                <td width="30%">
                                    <input name="es" type="text" id="es" value="" readonly
                                        class="form-control" />
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" class="form-control" wire:model='image' />
                            <input type="hidden" class="form-control" wire:model='template' />
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    var ws;
    $(document).ready(function() {

        // test if the browser supports web sockets
        if ("WebSocket" in window) {
            debug("Browser supports web sockets!", 'success');
            connect("ws://127.0.0.1:21187/fps");
            $('#console_send').removeAttr('disabled');
        } else {
            debug("Browser does not support web sockets", 'error');
        };

        // function to send data on the web socket
        function ws_send(str) {
            try {
                ws.send(str);
            } catch (err) {
                debug(err, 'error');
            }
        }

        // connect to the specified host
        function connect(host) {

            debug("Connecting to " + host + " ...");
            try {
                ws = new WebSocket(host); // create the web socket
            } catch (err) {
                debug(err, 'error');
            }
            $('#host_connect').attr('disabled', true); // disable the 'reconnect' button

            ws.onopen = function() {
                debug("connected... ", 'success');
            };

            ws.onmessage = function(evt) {
                var obj = eval("(" + evt.data + ")");
                var status = document.getElementById("es");
                switch (obj.Workmsg) {
                    case 1:
                        status.value = "Please Open Device";
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
                            status.value = "Get Template OK";
                            if (obj.Data1 != "null") {
                                var en2 = document.getElementById("e2");
                                @this.template1 = obj.Data1;
                            }
                        } else {
                            status.value = "Get Template Fail";
                        }
                        break;
                    case 6:
                        if (obj.Restmsg == 1) {
                            status.value = "Enrol Template OK";
                            if (obj.Data1 != "null") {
                                @this.template = obj.Data1;
                            }
                        } else {
                            status.value = "Enrol Template Fail";
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
                        status.value = "Time Out";
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
                }
            };

            ws.onclose = function() {
                debug("Socket closed!",
                    'error'
                );
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
        document.getElementById("es").value = "Place Finger";
    }

    function GetTemplate() {
        try {
            let cmd = "{\"cmd\":\"capture\",\"data1\":\"\",\"data2\":\"\"}";
            ws.send(cmd);
        } catch (err) {}
        document.getElementById("es").value = "Place Finger";
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
</script>
