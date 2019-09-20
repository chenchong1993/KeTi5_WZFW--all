/**
 * [声音提醒和提示框显示]
 * @param  {[type]} text [提示信息]
 * @param  {[type]} type [提示类型]
 * @return {[type]}      [description]
 */
function notify(text, type) {
    var level;
    var index_str;
    if (type == 'opt_ok') {
        level = 'success';
        index_str = "[ 系统消息 ]<br/>";
    } else if (type == 'opt_err') {
        level = 'warning';
        index_str = "[ 系统消息 ]<br/>";
    } else if (type == 'sys') {
        index_str = "[ 系统消息 ]<br/>";
        level = 'info';
    } else if (type == 'user') {
        index_str = "[ 收到用户消息 ]<br/>";
        level = 'info';
    } else if (type == 'danger') {
        index_str = "[ 警告信息 ]<br/>";
        level = 'warning';
    }

    setTimeout(function () {
        $.hulla.send(index_str + text, level);
    }, 100);
}

// 提示框初始化
$.hulla = new hullabaloo();

  
/**
 * [以表单形式提交参数]
 * @param  {[type]} url        [地址]
 * @param  {[type]} data_name  [参数名]
 * @param  {[type]} data_value [参数内容]
 * @return {[type]}            [null]
 */
function submit_as_form(url, data_name, data_value) {

    console.log(url,data_name,data_value);
    var form = '<form id="tmp_for_submit_form" method="post" action=" ' + url + ' " >' +
        '<input type="hidden" name="' + data_name + '" value=" ' + data_value + ' ">' +
        '{{ csrf_field() }}' +
        '</form>';
    $('body').append(form);
    $('#tmp_for_submit_form').submit();
}