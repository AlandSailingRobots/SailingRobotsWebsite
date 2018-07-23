let enabled = null;
let timeout = 6000;
let refreshTimer;

$(document).ready(function () {
    CheckOpenMenu();
    init();
    updateButton();

});

function init () {
    if (document.cookie.indexOf("enabled") == -1 ) {
        setCookie("timeout", timeout);
        setCookie("enabled", true);
        setCookie("counter", 1);
    }
    if (document.cookie.indexOf("enabled") >= 0 && document.getElementById("dataTable")) {
        enabled = JSON.parse(getCookie("enabled"));
    } else {
        enabled = false;
    }

    updateButton();
        runTimer(enabled);

    //======= DEBUG =========
    counter = JSON.parse(getCookie("counter"));
    counter++;
    setCookie("counter", counter++);
    console.log("current cookie: ");
    console.log(document.cookie);
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
    }, 6000);

    return timer;
}

function stopTimer (timer) {
    window.clearInterval(timer);
}

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
    if (enabled) {
        document.getElementById ("timeoutRefresh").innerText = " Auto refresh [ON]";
        document.getElementById ("timeoutRefresh").className = "fa fa-refresh";
    } else {
        document.getElementById ("timeoutRefresh").innerText = " Auto refresh [OFF]";
        document.getElementById ("timeoutRefresh").className = "fa fa-refresh fa-disabled";
    }
}

function CheckOpenMenu () {
    let checkClickInsideSidebarMenu = document.querySelector("#sidebar-menu");

    document.body.addEventListener('click', function (event) {
        if (checkClickInsideSidebarMenu.contains(event.target)) {
            if (enabled) {
                toggleTimeout ();
            }
        }
    });
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