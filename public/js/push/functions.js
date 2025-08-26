if ("serviceWorker" in navigator) {
    navigator.serviceWorker
        .register("/sw.js")
        .then((reg) => {
            // регистрация сработала
            console.log("Registration succeeded. Scope is " + reg.scope);
        })
        .catch((error) => {
            // регистрация прошла неудачно
            console.log("Registration failed with " + error);
        });
}

function requestPermissionPush() {
    Notification.requestPermission().then((permission) => {
        if (permission === "granted") {
            // get service worker
            navigator.serviceWorker.ready.then((sw) => {
                // subscribe
                sw.pushManager
                    .subscribe({
                        userVisibleOnly: true,
                        applicationServerKey:
                            "BFR94o6b7XhMNSAEfJIKd23wJEMtMZaee2rfclOG9hBAOUMs6DcS366fcPcf7gWHRrZQ6DQPvOYs0zFcUx0oqVU",
                    })
                    .then((subscription) => {
                        const key = subscription.getKey("p256dh");
                        const token = subscription.getKey("auth");
                        const contentEncoding =
                            (PushManager.supportedContentEncodings || [
                                "aesgcm",
                            ])[0];
                        const data = {
                            endpoint: subscription.endpoint,
                            public_key: key
                                ? btoa(
                                      String.fromCharCode.apply(
                                          null,
                                          new Uint8Array(key)
                                      )
                                  )
                                : null,
                            auth_token: token
                                ? btoa(
                                      String.fromCharCode.apply(
                                          null,
                                          new Uint8Array(token)
                                      )
                                  )
                                : null,
                            encoding: contentEncoding,
                        };
                        update_subscribe_push(data);
                    });
            });
        } else {
            alert("Разрешите уведомления в настройках брузера");
        }
    });
}

function update_subscribe_push(data) {
    $.ajax({
        url: `${APP_URL}/users/push`,
        type: "PUT",
        contentType: "application/json",
        data: JSON.stringify(data),
        success: function () {
            window.location.reload();
        },
        error: function (err) {
            const error = err?.responseJSON?.message;
            if (error) {
                alert(error);
            }
        },
    });
}

function remove_subscribe_push(endpoint) {
    $.ajax({
        url: `${APP_URL}/users/push`,
        type: "DELETE",
        contentType: "application/json",
        data: JSON.stringify({ endpoint }),
        success: function () {
            window.location.reload();
        },
        error: function (err) {
            const error = err?.responseJSON?.message;
            if (error) {
                alert(error);
            }
        },
    });
}

function urlBase64ToUint8Array(base64String) {
    var padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, "+")
        .replace(/_/g, "/");

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
}
