@extends('layouts.app')

@section('link_url','/menu/user')

<style>
    .container {
        text-align: center;
        margin: 0;
        padding: 40px 0px;
    }

    .qrCode {
        margin: 0 auto;
        width: 80%;
    }

    #qr-reader {
        width: 100%;
        height: 100%;
    }
</style>

@section('content')
<div class="container">
    <div class="qrCode">
        <div id="qr-reader"></div>
    </div>
    <div id="qr-message" style="margin-top: 20px; font-size: 18px;"></div>
    <div id="reservation-details"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const qrReader = new Html5Qrcode("qr-reader");

        qrReader.start({
                facingMode: "environment"
            }, {
                fps: 10,
            },
            (decodedText) => {
                sendQrDataToServer(decodedText);
            },
            (errorMessage) => {
                console.warn(`QRコードのスキャン中にエラーが発生しました; ${errorMessage}`);
            }
        ).catch((error) => {
            console.error("QRコードスキャナーの起動に失敗しました:", error);
        });

        function sendQrDataToServer(qrData) {
            fetch('/reservation/verify/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        qrData: qrData
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        showReservationDetails(data.data);
                    } else {
                        showMessage(data.message, data.success);
                    }
                })
                .catch((error) => {
                    console.error("サーバーへの送信中にエラーが発生しました:", error);
                });
        }

        function showReservationDetails(details) {
            const reservationElement = document.getElementById('reservation-details');
            reservationElement.innerHTML = `
                <p>予約ID: ${details.id}</p>
                <p>店舗名: ${details.restaurant_name}</p>
                <p>予約日時: ${details.date}</p>
                <p>人数: ${details.number}</p>
            `;
        }

        function showMessage(message, isSuccess = true) {
            const messageElement = document.getElementById('qr-message');
            messageElement.textContent = message;
            messageElement.style.color = isSuccess ? 'green' : 'red';
        }
    });
</script>
@endpush