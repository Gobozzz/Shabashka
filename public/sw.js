self.addEventListener("push", (event) => {
    notification = event.data.json();
    event.waitUntil(
        self.registration.showNotification(notification.title, {
            body: notification.body,
            icon: notification.data?.icon,
            data: {
                notifURL: notification.data?.url,
            },
        })
    );
});

self.addEventListener("notificationclick", (event) => {
    event.waitUntil(clients.openWindow(event.notification.data.notifURL));
});
