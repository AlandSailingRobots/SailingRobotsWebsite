let enabled = true;
let defaultTimeout = 50000;
let timeout = defaultTimeout


setCookie("timeout", timeout);
setCookie("enabled", true);

//JSON.parse("true"); //returns boolean true

function setCookie (name, value) {
    document.cookie = name + "=" + value + ";";
}

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

console.log(getCookie("timeout"));
console.log(getCookie("enabled"));
console.log(document.cookie);


document.getElementById ("timeoutBtn").onclick = function () {
    toggleTimeout ();
};

setTimeout(function () {
    window.location.reload();
}, timeout);

function toggleTimeout () {

    if (enabled) {
        enabled = false;
        document.getElementById ("timeoutBtn").innerText = "Auto refresh [OFF]";
        console.log("OFF");
    } else {
        enabled = true;
        document.getElementById ("timeoutBtn").innerText = "Auto refresh [ON]";
        console.log("ON");
    }
}