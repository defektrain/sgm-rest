<?php

/* @var $this yii\web\View */

use kartik\widgets\Select2;

$this->title = 'REST Console';

$this->registerJs("
    var url = '/frontend/web/';
    var oauth2_token = '';

    $('#send_button').click(function(){
        auth = $('#auth').val();
        method = $('#method').val();
        url_type = $('#url_type').val();
        url_basic = $('#url_basic').val();
        url_id = $('#url_id').val();

        if (method!=1 && auth == 'No Auth')
        {
            alert('Необходима авторизация!');
            return false;
        }

        if (url_id) url_id = '?id=' + url_id;

        data = getData(method);

        if (url_type == 'GET') {
            $.ajax({
               type: 'GET',
               url: url + url_basic + url_id,
               data: data,
               success: function(data, status, xhr){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + JSON.stringify(data));
               },
               error: function(xhr, status, errorThrown){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + errorThrown);
               }
            });
        } else if (url_type == 'POST') {
            $.ajax({
               type: 'POST',
               url: url + url_basic,
               data: data,
               success: function(data, status, xhr){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + JSON.stringify(data));
               },
               error: function(xhr, status, errorThrown){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + errorThrown);
               }
            });
        } else if (url_type == 'DELETE') {
            $.ajax({
               type: 'DELETE',
               url: url + url_basic + url_id,
               data: data,
               success: function(data, status, xhr){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + JSON.stringify(data));
               },
               error: function(xhr, status, errorThrown){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + errorThrown);
               }
            });
        } else if (url_type == 'PUT') {
            $.ajax({
               type: 'PUT',
               url: url + url_basic + url_id,
               data: data,
               success: function(data, status, xhr){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + JSON.stringify(data));
               },
               error: function(xhr, status, errorThrown){
                 $('#response_text').html(xhr.getAllResponseHeaders() + '<br><br>' + status + '<br><br>' + errorThrown);
               }
            });
        }
    });

    $('#method').change(function(){
        $('#query_param').html('');
        $('#query_value').html('');

        if ($('#auth').val()=='OAuth 2')
        {
            $('#query_param').append('\
                    <div class=\"form-group\">\
                        <input disabled name=\"params[]\" value=\"access_token\" class=\"form-control\">\
                    </div>\
                ');

            $('#query_value').append('\
                    <div class=\"form-group\">\
                        <input disabled name=\"values[]\" value=\"'+ oauth2_token +'\" class=\"form-control\">\
                    </div>\
                ');
        }

        if (this.value == 1) {
            $('#url_type').val('POST');
            $('#url_basic').val('site/signup');

            $('#query_param').html('');
            $('#query_value').html('');

            $('#query_param').append('\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"username\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"email\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"password\" class=\"form-control\">\
                </div>\
            ');

            $('#query_value').append('\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
            ');
        } else if (this.value == 2) {
            $('#url_type').val('GET');
            $('#url_basic').val('profile/view');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 3) {
            $('#url_type').val('PUT');
            $('#url_basic').val('profile/update');

            $('#query_param').append('\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"name\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"fio\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"birthday\" class=\"form-control\">\
                </div>\
            ');

            $('#query_value').append('\
                <div class=\"form-group\">\
                    <input id=\"profile_username\" name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input id=\"profile_fio\" name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input id=\"profile_birthday\" name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
            ');

            $('#body').append('<br><input type=\"file\" id=\"file_input\" name=\"Profile[imageFile]\">');
        } else if (this.value == 4) {
            $('#url_type').val('GET');
            $('#url_basic').val('project/list');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 5) {
            $('#url_type').val('GET');
            $('#url_basic').val('project/view');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 6) {
            $('#url_type').val('POST');
            $('#url_basic').val('project/create');

            $('#query_param').append('\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"executor_id\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"name\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"text\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"date_create\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"date_end\" class=\"form-control\">\
                </div>\
            ');

            $('#query_value').append('\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
            ');
        } else if (this.value == 7) {
            $('#url_type').val('PUT');
            $('#url_basic').val('project/update');

            $('#query_param').append('\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"executor_id\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"name\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"text\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"date_create\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"date_end\" class=\"form-control\">\
                </div>\
            ');

            $('#query_value').append('\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
            ');
        } else if (this.value == 8) {
            $('#url_type').val('DELETE');
            $('#url_basic').val('project/delete');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 9) {
            $('#url_type').val('GET');
            $('#url_basic').val('task/list');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 10) {
            $('#url_type').val('GET');
            $('#url_basic').val('task/view');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 11) {
            $('#url_type').val('POST');
            $('#url_basic').val('task/create');

            $('#query_param').append('\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"executor_id\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"project_id\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"name\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"text\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"date_end\" class=\"form-control\">\
                </div>\
            ');

            $('#query_value').append('\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
            ');
        } else if (this.value == 12) {
            $('#url_type').val('PUT');
            $('#url_basic').val('task/update');

            $('#query_param').append('\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"executor_id\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"comment\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"project_id\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"name\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"text\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input disabled name=\"params[]\" value=\"date_end\" class=\"form-control\">\
                </div>\
            ');

            $('#query_value').append('\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
                <div class=\"form-group\">\
                    <input name=\"values[]\" value=\"\" class=\"form-control\">\
                </div>\
            ');
        } else if (this.value == 13) {
            $('#url_type').val('DELETE');
            $('#url_basic').val('task/delete');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 14) {
            $('#url_type').val('GET');
            $('#url_basic').val('task-history/list');

            $('#query_param').html('');
            $('#query_value').html('');
        } else if (this.value == 15) {
            $('#url_type').val('GET');
            $('#url_basic').val('task-history/view');

            $('#query_param').html('');
            $('#query_value').html('');
        } else {
            $('#url_type').val('GET');
            $('#url_basic').val('');

            $('#query_param').html('');
            $('#query_value').html('');
        }
    });

    function getData(method) {
        data = {};
        params = $('input[name^=\"params\"]');
        values = $('input[name^=\"values\"]');

        $('input[name^=\"params\"]').each(function(index, value) {
            data[value.value] = values[index].value;
        });

        return data;
    }

    $('#auth').change(function(){
        if (this.value == 'OAuth 2') {
            $('#oauth2').fadeIn();
        } else {
            $('#oauth2').fadeOut();
            oauth2_token = '';
        }
    });

    $('#oauth2-get').click(function(){
        var oauth2data = {
            'grant_type': 'password',
            'username': $('#oauth2-username').val(),
            'password': $('#oauth2-password').val(),
            'client_id': 'testclient',
            'client_secret': 'testpass'
        };
        $.ajax({
               type: 'POST',
               url: url + 'oauth2/token',
               data: oauth2data,
               success: function(data, status, xhr){
                 $('#oauth2-result').html(status + ' - ' + JSON.stringify(data));
                 oauth2_token = data.access_token;
               },
               error: function(xhr, status, errorThrown){
                 $('#oauth2-result').html(status + ' - ' + errorThrown);
               }
            });
    });

", \yii\web\View::POS_END);
?>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="auth">Authentication</label>
            <select class="form-control" id="auth" name="auth">
                <option>No Auth</option>
                <option>HTTP Basic Auth</option>
                <option>OAuth 2</option>
            </select>
        </div>
    </div>
    <div class="col-md-10" id="oauth2" style="display: none;">
        <div class="col-md-2">
            <div class="form-group">
                <label for="auth">Username</label>
                <input class="form-control" type="text" id="oauth2-username">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="auth">Password</label>
                <input class="form-control" type="text" id="oauth2-password">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="auth">Get Access Token</label>
                <input class="form-control btn btn-warning" type="button" value="Get!" id="oauth2-get">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="auth">Token Result</label><br>
                <kbd id="oauth2-result" style="    display: block;
    padding: 10px;
    margin-top: 10px;
    white-space: pre;
    word-break: break-word;"></kbd>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="method">Select an API method</label>
            <?= Select2::widget([
                'id' => 'method',
                'name' => 'method',
                'data' => [
                    'Profile' => [
                        '1' => '[POST] Sign Up',
                        '2' => '[GET] View',
                        '3' => '[PUT] Edit'
                    ],
                    'Project' => [
                        '4' => '[GET] List',
                        '5' => '[GET] View',
                        '6' => '[POST] Create',
                        '7' => '[PUT] Edit',
                        '8' => '[DELETE] Delete'
                    ],
                    'Task' => [
                        '9' => '[GET] List',
                        '10' => '[GET] View',
                        '11' => '[POST] Create',
                        '12' => '[PUT] Edit',
                        '13' => '[DELETE] Delete'
                    ],
                ],
                'options' => ['placeholder' => 'No Selected Method'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="url_type">Request URL</label>
            <select class="form-control" id="url_type" name="url_type">
                <option>GET</option>
                <option>POST</option>
                <option>DELETE</option>
                <option>PUT</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <label for="url_basic">&nbsp;</label>

        <div class="input-group">
            <span class="input-group-addon" id="url_basic-addon3">/frontend/web/</span>
            <input type="text" class="form-control" id="url_basic" name="url_basic" aria-describedby="url_basic-addon3">
        </div>
    </div>
    <div class="col-md-2">
        <label for="url_basic">&nbsp;</label>

        <div class="input-group">
            <span class="input-group-addon" id="url_basic-addon3">id:</span>
            <input type="text" class="form-control" id="url_id" name="url_id" aria-describedby="url_basic-addon3">
        </div>
    </div>
    <div class="col-md-1">
        <label for="send_button">&nbsp;</label>

        <div class="form-group">
            <input type="submit" class="form-control btn-success" id="send_button" value="Send">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#query" aria-controls="home" role="tab" data-toggle="tab">Query</a></li>
            <li role="presentation"><a href="#body" aria-controls="messages" role="tab" data-toggle="tab">Body</a></li>
        </ul>

        <form id="file_form" name="file_form" method="post" enctype="multipart/form-data">
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="query">
                    <br/>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Parameter</label>
                            <div id="query_param">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Value</label>
                            <div id="query_value">

                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="body">

                </div>
            </div>
        </form>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-md-6">
        <div class="well well-sm">
            <b>Response</b>
            <kbd style="display: block;padding: 10px;margin-top: 10px;white-space: pre;word-break: break-word;" id="response_text">

            </kbd>
        </div>
    </div>
</div>