<?php
if (!isset($this->session->userdata['logged_in'])) {

    header("location: login");
}
?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-2">
            <div class="btn-group">
                <button type="button" class="btn btn-success " id="username">
                    <span class="glyphicon glyphicon-user"></span>
                </button>


            </div>

        </div>

        <div class="col-sm-9 col-md-10">
            <!-- Split button -->

            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Refresh" id="refresh">
                   <span class="glyphicon glyphicon-refresh"></span>   </button>
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    More <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Mark all as read</a></li>
                    <li class="divider"></li>
                    <li class="text-center"><small class="text-muted">Select messages to see more actions</small></li>
                </ul>
            </div>
            <div class="pull-right">
                <span class="text-muted"><b>1</b>–<b>50</b> of <b>277</b></span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr />

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">Compose</h4>
                </div>
                <div class="modal-body">
                    <form role="form" class="form-horizontal" id="compose-form">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">To</label>
                            <div class="col-lg-10">
                                <input type="text" placeholder="david,mark,tony" id="receivers" name="receivers" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Subject</label>
                            <div class="col-lg-10">
                                <input type="text" placeholder="" id="subject" name="subject" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Message</label>
                            <div class="col-lg-10">
                                <textarea rows="10" cols="30" class="form-control" id="body" name="body"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                                      <span class="btn green fileinput-button">
                                                        <i class="fa fa-plus fa fa-white"></i>
                                                        <span>Attachment</span>
                                                        <input type="file" name="files[]" multiple="">
                                                      </span>
                                <button class="btn btn-send" type="submit">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="row">
        <div class="col-sm-3 col-md-2">
            <button type="button" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#myModal" >COMPOSE</button>

            <hr />
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#received" data-toggle="tab" id="received"><span class="badge pull-right"></span> Inbox </a>
                </li>
                <li><a href="#sent" data-toggle="tab" id="sent">Sent Mail</a></li>
                <li><a href="#draft" data-toggle="tab" id="draft"><span class="badge pull-right"></span>Drafts</a></li>
                <li><a href="#trash" data-toggle="tab" id="deleted">Trash</a></li>
            </ul>
            <hr/>

            <button type="button" class="btn btn-info btn-sm" id="logout">
                <span class="glyphicon glyphicon-log-out"></span> Log out
            </button>



        </div>
        <div class="col-sm-9 col-md-10">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab"><span class="glyphicon glyphicon-inbox">
                </span>Primary</a></li>

            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="list-group" id="mails">

                    </div>
                </div>

            </div>


        </div>
    </div>
</div>
<style>
    body{ margin-top:50px;}
    .nav-tabs .glyphicon:not(.no-margin) { margin-right:10px; }
    .tab-pane .list-group-item:first-child {border-top-right-radius: 0px;border-top-left-radius: 0px;}
    .tab-pane .list-group-item:last-child {border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;}
    .tab-pane .list-group .checkbox { display: inline-block;margin: 0px; }
    .tab-pane .list-group input[type="checkbox"]{ margin-top: 2px; }
    .tab-pane .list-group .glyphicon { margin-right:5px; }
    .tab-pane .list-group .glyphicon:hover { color:#FFBC00; }
    a.list-group-item.read { color: #222;background-color: #F3F3F3; }
    hr { margin-top: 5px;margin-bottom: 10px; }
    .nav-pills>li>a {padding: 5px 10px;}

    .ad { padding: 5px;background: #F5F5F5;color: #222;font-size: 80%;border: 1px solid #E5E5E5; }
    .ad a.title {color: #15C;text-decoration: none;font-weight: bold;font-size: 110%;}
    .ad a.url {color: #093;text-decoration: none;}
    .attachment-mail {
        margin-top: 30px;
    }
    .attachment-mail ul {
        display: inline-block;
        margin-bottom: 30px;
        width: 100%;
    }
    .attachment-mail ul li {
        float: left;
        margin-bottom: 10px;
        margin-right: 10px;
        width: 150px;
    }
    .attachment-mail ul li img {
        width: 100%;
    }
    .attachment-mail ul li span {
        float: right;
    }
    .attachment-mail .file-name {
        float: left;
    }
    .attachment-mail .links {
        display: inline-block;
        width: 100%;
    }

    .fileinput-button {
        float: left;
        margin-right: 4px;
        overflow: hidden;
        position: relative;
    }
    .fileinput-button input {
        cursor: pointer;
        direction: ltr;
        font-size: 23px;
        margin: 0;
        opacity: 0;
        position: absolute;
        right: 0;
        top: 0;
        transform: translate(-300px, 0px) scale(4);
    }
    .fileupload-buttonbar .btn, .fileupload-buttonbar .toggle {
        margin-bottom: 5px;
    }
    .files .progress {
        width: 200px;
    }
    .fileupload-processing .fileupload-loading {
        display: block;
    }
    * html .fileinput-button {
        line-height: 24px;
        margin: 1px -3px 0 0;
    }
    * + html .fileinput-button {
        margin: 1px 0 0;
        padding: 2px 15px;
    }
    @media (max-width: 767px) {
        .files .btn span {
            display: none;
        }
        .files .preview * {
            width: 40px;
        }
        .files .name * {
            display: inline-block;
            width: 80px;
            word-wrap: break-word;
        }
        .files .progress {
            width: 20px;
        }
        .files .delete {
            width: 60px;
        }
    }
    ul {
        list-style-type: none;
        padding: 0px;
        margin: 0px;
    }
    .btn:focus {
        outline: none;
    }


</style>
<script>

    $('document').ready(function() {
        $('.nav-pills a[href="'+location.href+'"]').parents('li').addClass('active')
        function getMails(type) {
            $.ajax({
                type:'GET',
                url:'mail/mails?type='+type,
                success:function (data) {
                    $('#username ').html('<span class="glyphicon glyphicon-user">'+data.user.name+'</span>')
                    $('#received span').html(data.count.unread);
                    $('#draft span').html(data.count.draft);
                    if(data.status==0){
                        $('#mails a').remove();
                        $('#mails').append('<a><span class="name" style="min-width: 120px;display: inline-block;">There is no message</span></a>');
                    }
                    else{
                        $('#mails a').remove();
                        $.each(data.mails, function(index,mail) {
                            //console.log(mail.id)
                            $('#mails').append('<a href="#" class="list-group-item" > <div class="checkbox"> <label> ' +
                                '<input type="checkbox"> ' +
                                '</label> </div> <span class="glyphicon glyphicon-star-empty"></span><span class="name" style="min-width: 120px;display: inline-block;">'+(type == "sent" ? 'To- ': '')+mail.name+'</span>' +
                                ' <span class="">'+mail.subject.substring(0,20)+'...</span> ' +
                                '<span class="text-muted" style="font-size: 11px;">-'+mail.body.substring(0,30)+'.....</span> ' +
                                '<span class="badge">'+mail.sentAt+'</span> <span class="pull-right">'+(mail.attached==0? ' ':'<span class="glyphicon glyphicon-paperclip"></span>')+' </span></a>')
                        });
                    }


                }
            });


        }
        getMails("received");

        $("#compose-form").validate({
            rules: {
                receivers: {
                    required: true,
                },
                subject: {
                    required: true,

                },
                body: {
                    required: true,
                }
            },
            messages: {
                receivers: {
                    required: "please enter username of receiver"
                },
                subject: "please enter subject",
            },
            submitHandler: submitForm
        });
        function submitForm() {
            var data = $("#compose-form").serialize();

            $.ajax({

                type: 'POST',
                url: 'mail/mails',
                data: data,

                success: function (response) {
                    if (response) {
                        console.log(response)


                        $('#myModal').modal('hide');

                    }
                    else {


                    }
                }
            });
            return false;
        }
        $("#refresh").click(function() {
            t=$("ul.nav-pills li.active a")[0].id
            getMails(t)
        });
        $("#sent").click(function() {
            t=$("ul.nav-pills li.active a")[0].id
            getMails("sent")
        });
        $("#received").click(function() {
            t=$("ul.nav-pills li.active a")[0].id
            getMails("received")
        });
        $("#draft").click(function() {
            t=$("ul.nav-pills li.active a")[0].id
            getMails("draft")
        });
        $("#deleted").click(function() {
            t=$("ul.nav-pills li.active a")[0].id
            getMails("deleted")
        });
        $("#logout").click(function() {
            $.ajax({

                type: 'GET',
                url: 'auth',


                success: function () {
                    window.location.href = "/gmail";
                }
            });
        });


    });
</script>