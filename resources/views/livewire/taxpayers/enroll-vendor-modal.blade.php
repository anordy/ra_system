<div>
    @push('styles')
        <link href="{{ asset('css/registration.css') }}" rel="stylesheet">
    @endpush
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Enroll {{ $hand }} Hand {{ $finger }} @if ($finger != 'thumb')
                        Finger
                    @endif
                </h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row p-2">
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

                    <div class="col-md-12">
                        <p align="center">
                        <table align="center" bordercolor="#000000" class="rg-table" border="1"
                            cellspacing="0" cellpadding="1">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="box">
                                            @if ($image)
                                                <img src="data:image/png;base64,{{ $image }}" alt=""
                                                    class="vertical-img" />
                                            @else
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAkCAYAAABIdFAMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNo8zjsOxCAMBFB/KEAUFFR0Cbng3nQPw68ArZdAlOZppPFIBhH5EAB8b+Tlt9MYQ6i1BuqFaq1CKSVcxZ2Acs6406KUgpt5/LCKuVgz5BDCSb13ZO99ZOdcZGvt4mJjzMVKqcha68iIePB86GAiOv8CDADlIUQBs7MD3wAAAABJRU5ErkJggg=="  class="vertical-img"
                                                     align="middle" />
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br />
                        </p>
                        <p align="center">
                            <textarea readonly="readonly" class="form-controlZ" rows="1" name="state" id="state" cols="50"></textarea>
                        </p>

                        <p align=center>
                            <small>NB: In order to enroll user should place twice the finger in the devices as guided by
                                display above</small>
                            <br>
                            <br>
                            <input type="button" class="btn btn-outline-info" value="Click to Enrol Fingerprint"
                                name="B1" id="EnrollTemplate" />
                            <br />
                        </p>

                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save changes
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript" nonce="custom_script">
    var ws;
    $(document).ready(function() {

        if ("WebSocket" in window) {
            connect("ws://127.0.0.1:21187/fps");
        } else {
            $('#state').val('Error: Browser not supported!');
        };

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
                switch (obj.workmsg) {
                    case 1:
                        status.value = "Please connect the device";
                        break;
                    case 2:
                        status.value = "Place Finger ...";
                        break;
                    case 3:
                        status.value = "Lift Finger ...";
                        break;
                    case 4:
                        //status.value = "";
                        break;
                    case 5:
                        if (obj.retmsg == 1) {
                            status.value = "Capture succeeded";
                            if (obj.data1 == "null") {

                            } else {
                                var en2 = document.getElementById("matchdata");
                                @this.template = obj.data1;
                            }
                        } else {
                            status.value = "Capture fail"
                        }
                        break;
                    case 6:
                        if (obj.retmsg == 1) {
                            status.value = "Enrol succeeded";
                            if (obj.data1 == "null") {

                            } else {
                                var en1 = document.getElementById("enroldata");
                                @this.template = obj.data1;
                            }
                        } else {
                            status.value = "Enrol  fail";
                        }
                        break;
                    case 7:
                        if (obj.image == "null") {} else {
                            @this.image = obj.image;

                        }
                        break;
                    case 8:
                        status.value = "Time Out";
                        break;
                    case 9:
                        status.value = "Match score:" + obj.retmsg;
                        break;
                    case 10:
                        if (obj.image == "null") {} else {
                            var en4 = document.getElementById("e4");
                            en4.value = obj.image;
                            @this.image = obj.image;
                        }
                        break;
                    case 15:
                        if (obj.retmsg == 1) {
                            status.value = "Reconnect device succeeded";
                        } else {
                            status.value = "Reconnect device fail";
                        }
                        break;
                    case 18:
                        if (obj.image == "null") {} else {
                            @this.image = obj.image;
                        }
                        break;
                    case 19:
                        status.value = obj.image;
                        break;
                }
            };

            ws.onclose = function() {
                $('#state').val('Error:Close!');
            };
        };

        $('#EnrollTemplate').on('click', function() {
            EnrollTemplate();
        })

    });

    function Relink() {
        ws.close();
        connect("ws://127.0.0.1:21187/fps");
    }


    function EnrollTemplate() {
        try {
            var v1 = "0";
            var v2 = "0";
            var cmd = "{\"cmd\":\"enrol\",\"data1\":\"" + v1 + "\",\"data2\":\"" + v2 + "\"}";
            ws.send(cmd);
        } catch (err) {}
        document.getElementById("state").value = "Place Finger";
    }

    function GetTemplate() {
        try {
            var v1 = "1";
            var v2 = "0";
            var cmd = "{\"cmd\":\"capture\",\"data1\":\"" + v1 + "\",\"data2\":\"" + v2 + "\"}";
            ws.send(cmd);
        } catch (err) {}
        document.getElementById("state").value = "Place Finger";
    }

    function MatchTemplate() {
        var v1 = document.getElementById("enroldata").value;
        var v2 = document.getElementById("matchdata").value;
        var cmd = "{\"cmd\":\"match\",\"data1\":\"" + v1 + "\",\"data2\":\"" + v2 + "\"}";
        try {
            ws.send(cmd);
        } catch (err) {}
    }


    function RelinkDevice() {
        try {
            var cmd = "{\"cmd\":\"opendevice\",\"data1\":\"\",\"data2\":\"\"}";
            ws.send(cmd);
        } catch (err) {}
    }

    function GetDeviceSN() {
        try {
            var cmd = "{\"cmd\":\"getsn\",\"data1\":\"\",\"data2\":\"\"}";
            ws.send(cmd);
        } catch (err) {}
    }

    function AboutImage() {
        try {
            var cmd = "{\"cmd\":\"aboutimage\",\"data1\":\"\",\"data2\":\"\"}";
            ws.send(cmd);
        } catch (err) {}
    }
</script>
