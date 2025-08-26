const payment_type_more_all = Array.from(
    document.querySelectorAll(".payment_type_more")
);

function update_visible_payments_type_more() {
    payment_type_more_all.map((elem) => (elem.style.display = "none"));
    const active_payment = document.querySelector(
        `[name="payment_type"]:checked`
    );
    if (!active_payment) return;
    const value = active_payment.value;
    if (value === FIXED_PAYMENT) {
        const fixed = document.querySelector(".payment_type_fixed_more");
        if (fixed) {
            fixed.style.display = "block";
        }
    }
}

update_visible_payments_type_more();
