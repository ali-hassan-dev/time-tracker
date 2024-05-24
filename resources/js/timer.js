document.addEventListener("DOMContentLoaded", function () {
    const timerButton = document.getElementById("timer-button");

    let timerState = "stopped";
    let timeLogId = null;

    timerButton.addEventListener("click", function () {
        if (timerState === "stopped") {
            startTimer();
        } else {
            stopTimer();
        }
    });

    function startTimer() {
        fetch("/timelogs/start", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                timeLogId = data.id;
                timerButton.textContent = "Stop Work";
                timerState = "started";
            })
            .catch((error) => console.error("Error:", error));
    }

    function stopTimer() {
        fetch(`/timelogs/${timeLogId}/stop`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                timerButton.textContent = "Start Work";
                timerState = "stopped";
            })
            .catch((error) => console.error("Error:", error));
    }

    timerButton.textContent = "Start Work";
});