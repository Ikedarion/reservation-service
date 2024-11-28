var stripe = Stripe('pk_test_51QLztUF8XhjTFbz9sWhKEp3DJMbsmB6mqasfHsv5BLjTKP5nrDcrZ4oYx9QAutdhV2r9Eoa1qNtmm0L4oQ8Vv4OQ00XkugHsgN');

var form = document.querySelector('.res__form');

document.querySelector('.res__form').addEventListener('submit', function(event) {
    event.preventDefault();

    fetch("/create-checkout-session", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            restaurant_id: form.restaurant_id.value,
            date: form.date.value,
            time: form.time.value,
            number: form.number.value,
        }),
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                if (data.errors) {
                    if (data.errors.date) {
                        document.getElementById("error-date").textContent = data.errors.date[0];
                        document.getElementById("error-date").style.backgroundColor = "rgb(255, 56, 56)";
                    } else {
                        document.getElementById("error-date").textContent = '';
                        document.getElementById("error-date").style.backgroundColor = "";
                    }

                    if (data.errors.time) {
                        document.getElementById("error-time").textContent = data.errors.time[0];
                        document.getElementById("error-time").style.backgroundColor = "rgb(255, 56, 56)";
                    } else {
                        document.getElementById("error-time").textContent = '';
                        document.getElementById("error-time").style.backgroundColor = "";
                    }

                    if (data.errors.number) {
                        document.getElementById("error-number").textContent = data.errors.number[0];
                        document.getElementById("error-number").style.backgroundColor = "rgb(255, 56, 56)";
                    } else {
                        document.getElementById("error-number").textContent = '';
                        document.getElementById("error-number").style.backgroundColor = "";
                    }
                }
            });
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data.message) {
            console.log(data.message);
            alert('予期しないエラーが発生しました。後ほど再試行してください。');
        } else {
            return stripe.redirectToCheckout({ sessionId: data.id });
        }
    })
    .then(result => {
        if (result.error) {
            console.error(result.error.message);
            alert('予期しないエラーが発生しました。後ほど再試行してください。');
        }
    })
    .catch(error => {
        if (error && error.response && error.response.status === 422) {
            console.error("バリデーションエラー:", error.response.data.errors);
        } else if (error.response) {
            console.error("エラー:", error.response.data);
        } else if (error.request) {
            console.error("リクエストエラー:", error.request);
        }
    });
});