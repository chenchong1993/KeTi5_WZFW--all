/**
 *
 * Created by Administrator on 2018/4/5 0005.
 */

function Toast(msg, duration) {
    duration = isNaN(duration) ? 3000 : duration;
    var m = document.createElement('div');
    m.innerHTML = msg;
    m.style.cssText = "width: 25%;min-width: 150px;opacity: 0.7;height: 50px;color: rgb(255, 255, 255);line-height: 50px;text-align: center;border-radius: 5px;position: fixed;top: 40%;left: 30%;z-index: 999999;background: rgb(0, 0, 0);font-size: 20px;";
    document.body.appendChild(m);
    setTimeout(function () {
        var d = 0.5;
        m.style.webkitTransition = '-webkit-transform ' + d + 's ease-in, opacity ' + d + 's ease-in';
        m.style.opacity = '0';
        setTimeout(function () {
            document.body.removeChild(m)
        }, d * 1000);
    }, duration);
}

function getNowTime() {
    function p(s) {
        return s < 10 ? '0' + s : s;
    }

    var myDate = new Date();
    return myDate.getFullYear() + '-' + p(myDate.getMonth() + 1) + "-" + p(myDate.getDate()) + " " + p(myDate.getHours()) + ':' + p(myDate.getMinutes()) + ":" + p(myDate.getSeconds());
}

