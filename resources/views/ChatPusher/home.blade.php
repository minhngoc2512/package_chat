<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://unpkg.com/vue"></script>
{{cookie('laravel_session')}}




<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">


        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create group</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label> Enter Name</label>
                    <input type="text" id="name_group" class="form-control" placeholder="Enter name" >
                </div>
                <div class="form-group">
                    <input  class="btn btn-success" onclick="getFormSendGroup()" value="Create Group" data-dismiss="modal">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>
<div
            style="z-index: 900 ;position: fixed;
    bottom: 5px;
    right: 0px;

    -webkit-appearance: none;
            font-size: 1rem;
            line-height: 1.2;
            border-radius: 3px;


            color: white;
            cursor: pointer;
            white-space: nowrap;
            text-overflow: ellipsis;
            text-decoration: none !important;
            cursor: pointer;
            text-align: right;
            font-weight: normal;
            padding: 10px 16px;
            padding-left: 30px;
            outline: 0;" >



            <ul>
                <li>
                    <ul class="list-group" style="list-style-type: none;" id="list-member">


                    </ul>
                </li>
                <li><button class="btn btn-danger " style="width: 140px" id="online"> Connecting...</button></li>
            </ul>


        <ul>
            <li id="option">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-success" style="font-size: 14px;font-weight: 400;"> <b onclick="Member_Info()" ><i class="glyphicon glyphicon-refresh"></i> Refresh </b></li>
                    <li class="list-group-item list-group-item-danger" data-toggle="modal" data-target="#myModal" style="font-size: 14px;font-weight: 400;"><i class="glyphicon glyphicon-cloud" ></i>Create Group</li>
                </ul>
            </li>
            <li>
                <button class="btn btn-info" style="width: 140px" onclick="showOption()" ><i class="glyphicon glyphicon-cog"></i> Option </button>

            </li>
        </ul>


    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>

        $(document).ready(function(){
            $('#list-member').hide();
            $('#online').click(function () {
                $('#list-member').slideToggle(300);
            });
        });
    </script>




    <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


    <script>
        Notification.requestPermission();

        //Private Chat
        let pusher_private = new Pusher('{{env('PUSHER_APP_KEY')}}', {
            authEndpoint: 'broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            },
            cluster: 'ap1',
            encrypted: true
        });




    </script>
    <script>

    </script>
    <div  style="position: fixed;float:left;z-index: 10;width: 100%">
        <ul id="chat-message" style="list-style-type: disc;" class="list-group">

        </ul>
    </div>



    <script>


        var ListChannel = [];
        var ListChannelStatus = [];
        var Name_ChannelSend_Private;
        var ListFormStatus = [];

        function getFormSendGroup(){
             var nameChannel = $("#name_group").val();

            $.get('FormSendGroup/'+nameChannel,function(data){
                $('#chat-message').append(data);

            });
        }


        function sendmessage_private(user_to,NameChannel) {

            var message = $('#text-message-'+NameChannel).val();
            $('#text-message-'+NameChannel).val('');
            $('#list-message-'+NameChannel).append('<li class="alert alert-danger small"> '+message+' </li>').scrollTop(9999);
            $.post('sendmessage', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                //  task: 'comment_insert',
                message: message,
                to_user:user_to,
                name:NameChannel
            }, function (data, status) {


                console.log("Data: " + data + "\nStatus: " + status);
            });
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Send event presence
        function sendEventPresence(message,NameChannel,checkChannel,NameChannelGroup=null,ListUser=null){
            //Check form chat exist!

            if(NameChannelGroup===null){
            if( message!='{{auth::user()->name}}'){


                $.post('message', {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    NameChannel: NameChannel,
                    message: message,
                    CheckChannel: checkChannel
                }, function (data, status) {
                    console.log("Data: " + data + "\nStatus: " + status);
                    Name_ChannelSend_Private = data;

                    var statusForm = 0;
                    for(i=0;i<ListFormStatus.length;i++){
                            if(ListFormStatus[i]===Name_ChannelSend_Private){
                                statusForm=1;
                                break;
                            }
                    }

                    if(statusForm===0) {
                        if ($('#message-content-' + Name_ChannelSend_Private).attr('class') == null) {
                            ListFormStatus[ListFormStatus.length] = Name_ChannelSend_Private;
                            $.get('FormSend/' + message + '/' + Name_ChannelSend_Private, function (data) {


                                var data2 = data.split("+++");

                                $('#chat-message').append(data2[0]);
                                $('#list-message-' + Name_ChannelSend_Private).append(data2[1]).scrollTop(9999);

                                //  this.Channel_Private = NameChannel;
                                var j = 0;
                                for (i = 0; i < ListChannel.length; i++) {
                                    if (ListChannel[i] === NameChannel) {
                                        j = 1;
                                        break;

                                    }
                                }
                                if (j === 0) {

                                    var PrivateChannel = pusher_private.subscribe('private-chat.' + Name_ChannelSend_Private);
                                    PrivateChannel.bind("Minh\\PusherChat\\Event\\SentMessage", function (data) {
                                        if ($('#message-content-' + Name_ChannelSend_Private).attr('class') == null) {
                                            $.get('FormSend/' + message + '/' + Name_ChannelSend_Private, function (data) {


                                                var data2 = data.split("+++");

                                                $('#chat-message').append(data2[0]);
                                                $('#list-message-' + Name_ChannelSend_Private).append(data2[1]).scrollTop(9999);
                                            });

                                        }

                                        if (data.user.name != '{{auth::user()->name}}') {

                                            $('#list-message-' + Name_ChannelSend_Private).append(' <li class="alert alert-info small"> ' + data.message + ' </li>').scrollTop(9999);
                                        }
                                        ListChannel[ListChannel.length] = NameChannel;
                                    });
                                }
                            });
                        }
                    }
                });
             }
            }
        }

        //pusher-presence
        let pusher=null;
        function CreatePresence(){
            Pusher.logToConsole = true;
            pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
                authEndpoint: 'broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                cluster: 'ap1',
                encrypted: true

            });
        }

        //Status connect
        function StatusConnect(){
            pusher.connection.bind('connected', function () {

                Member_Info();

            });
            pusher.connection.bind('failed', function () {
                Member_Info();
                $('div#status').text('failed,Error connect!')
            });
            pusher.connection.bind('unavailable', function () {
                Member_Info();
                $('div#status').append('<label  class="alert alert-danger"> Error! Check connect internet!!!</label>');
            });

            pusher.connection.bind('disconnected', function () {
                Member_Info();
                $('div#status').text('Disconnected!Error connect!')
            });
            pusher.connection.bind('initialized', function () {
                Member_Info();
                $('div#status').text('Initialized,Error connect!')
            });


        }

        //Received message from presence channel
        let channel = null;
        function getNoticationPresence(){
            channel = pusher.subscribe('presence-memberOnline.1');
            channel.bind('Minh\\PusherChat\\Event\\MemberOnline', function (data) {
                Member_Info();


                if ('{{auth::user()->name}}' == data.user2) {

                    var NameChannel = data.NameChannel;
                    //  this.Channel_Private = NameChannel;
                    var CheckChannel = data.CheckChannel;

                    //Check form chat exist! CheckChannel
                    {{--@if(Session::has('channelChat'))--}}
                            {{--@foreach(Session::get('channelChat') as $data)--}}
                            {{--@if($data)--}}
                            {{--@endif--}}
                            {{----}}
                            {{----}}
                            {{--@endforrach--}}
                            {{----}}
                        {{--@endif--}}



                    if($('#message-content-'+NameChannel).attr('class')==null&& $('#message-content-'+CheckChannel).attr('class')==null) {
                        var j=0;
                        for(i=0;i<ListChannel.length;i++) {
                            if (ListChannel[i] === NameChannel) {
                                j=1;
                                break;
                            }
                        }
                        if(j===0) {
                            ListChannel[ListChannel.length] = NameChannel;
                            var PrivateChannel = pusher_private.subscribe('private-chat.' + NameChannel);
                            PrivateChannel.bind("Minh\\PusherChat\\Event\\SentMessage", function (data) {
                                if (data.user.name != '{{auth::user()->name}}') {
                                    if ($('#message-content-' + NameChannel).attr('class') == null && $('#message-content-' + CheckChannel).attr('class') == null) {
                                        $.get('FormReceive/' + data.user.name + '/' + NameChannel, function (data) {
                                            var data2 = data.split("+++");
                                            $('#chat-message').append(data2[0]);
                                            $('#list-message-' + NameChannel).append(data2[1]).scrollTop(9999);
                                        });
                                    }
                                    $('#list-message-' + NameChannel).append(' <li class="alert alert-info small"> ' + data.message + ' </li>').scrollTop(9999);
                                }
                            });
                        }
                    }


                }
            });

        }

        //PRESENCE CHANNEL
        function Member_Info() {
            var count = channel.members.count;
            //  $('div#status').innerHTML="<button class=\"btn btn-success\"   >Member online:"+count+" </button>";
            $('#online').text(" Online:"+count);

           // document.getElementById('status').innerHTML = "<button class=\"btn btn-success\"  >Member online:" + count + " </button>";
            var infor = "";
            channel.members.each(function (member) {
                var userInfo = member.info;

                infor +="  <li style=\"margin-bottom: 5px;\" ><button class=\"btn btn-success\" id=\"chat\" style=\"width: 140px\"   onclick=\"sendEventPresence('"+userInfo.name+"','"+userInfo.name+"-{{auth::user()->name}}','{{auth::user()->name}}-"+userInfo.name+"');\"  ><i class='glyphicon glyphicon-user'></i> " + userInfo.name + " </button></li>" ;
                //infor += "<button class=\"btn btn-success\" id=\"chat\"   onclick=\"sendEventPresence('"+userInfo.name+"','"+userInfo.name+"-{{auth::user()->name}}','{{auth::user()->name}}-"+userInfo.name+"');\"  >" + userInfo.name + " </button>";
            });
            // alert(infor);
            document.getElementById('list-member').innerHTML = infor;
        }


        function _Notication() {
            channel.bind('pusher:member_added', function (member) {
                new Notification('Thông báo từ laravel',
                    {
                        body: 'Member:'+member.info.name+' online!!', // Nội dung thông báo
                        icon: 'http://iconizer.net/files/Simplicio/orig/notification_warning.png'// Hình ảnh

                    }
                );

                Member_Info();
            });
            channel.bind('pusher:member_removed', function (member) {
                new Notification('Thông báo từ Laravel',
                    {
                        body: 'Member:'+member.info.name+' offline!!', // Nội dung thông báo
                        icon: 'http://iconizer.net/files/Simplicio/orig/notification_warning.png' // Hình ảnh

                    });


                Member_Info();
            });

        }
        function EventRead(name,Channel) {
            $.get('changeStatusMessage/'+name+'/'+Channel,function(data){
                console.log(data);
            });

        }
        function showOption(){

                $('#option').toggle(500);


        }
        function getFormRefresh(user,Channel){
            var PrivateChannel = pusher_private.subscribe('private-chat.' + Channel);
            PrivateChannel.bind("Minh\\PusherChat\\Event\\SentMessage", function (data) {
                if ($('#message-content-' + Channel).attr('class') == null) {
                    $.get('FormSend/' + user + '/' + Channel, function (data) {


                        var data2 = data.split("+++");

                        $('#chat-message').append(data2[0]);
                        $('#list-message-' + Channel).append(data2[1]).scrollTop(9999);
                    });

                }
                if (data.user.name != '{{auth::user()->name}}') {

                    $('#list-message-' + Channel).append(' <li class="alert alert-info small"> ' + data.message + ' </li>').scrollTop(9999);
                }

            });
            ListChannel[ListChannel.length] = Channel;
        }

        function checkFormRefresh(){
            @if(Session::has('channelChat'))
                    @php(
                    $channelRefresh = Session::get('channelChat')
                    )
                 @foreach($channelRefresh as $key=> $data)
                    console.log({{$key}}+','+'{{$data['user']}}');
                    getFormRefresh('{{$data['user']}}','{{$data['channel']}}');
                @endforeach
            @endif


        }

        $('#option').hide();



        CreatePresence();
        checkFormRefresh();
        getNoticationPresence();
        StatusConnect();
        _Notication();


    </script>


