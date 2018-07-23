let enabled = null;
let timeout = 6000;
let refreshTimer;


$(document).ready(function () {
    enabled = JSON.parse(getCookie("enabled"));
    init();
    updateButton();

});

function init () {
    if (document.cookie.indexOf("enabled") == -1 ) {
        setCookie("timeout", timeout);
        setCookie("enabled", true);
        setCookie("counter", 1);
        document.getElementById ("timeoutBtn").innerText = "Auto refresh [init = ON]";
    }
    updateButton();
    runTimer(enabled);

    //======= DEBUG =========
    console.log("current cookie: ");
    console.log(document.cookie);
    counter = JSON.parse(getCookie("counter"));
    counter++;
    setCookie("counter", counter++);
    //=======================

}

function runTimer (bool) {
    window.onload = function () {
        if (bool == true) {
            refreshTimer = startTimer();
        } else {
            stopTimer(refreshTimer);
        }
    }
}

function startTimer () {
    let timer = setInterval(function () {
        window.location.reload();
    }, 3000);

    return timer;
}

function stopTimer (timer) {
    window.clearInterval(timer);
}

function setCookie (name, value) {
    document.cookie = name + "=" + value + ";";
}

function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

document.getElementById ("timeoutBtn").onclick = function () {
    toggleTimeout ();
};

function toggleTimeout () {
    enabled = JSON.parse(getCookie("enabled"));

        if (enabled) {
            stopTimer(refreshTimer);
            setCookie("enabled", false);
            enabled = false;
            updateButton();
        } else {
            setCookie("enabled", true);
            enabled = true;
            updateButton();
            refreshTimer = startTimer(timeout);
        }
}

function updateButton () {
    if (enabled == true) {
        document.getElementById ("timeoutBtn").innerText = "Auto refresh [ON]";
    } else {
        document.getElementById ("timeoutBtn").innerText = "Auto refresh [OFF]";
    }
}