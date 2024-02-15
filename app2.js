function checkAvailability() {
    var form = document.getElementById("availabilityForm");
    var formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if ('availableTimes' in data) {
            var availableTimesArray = data.availableTimes;
            populateTimeOptions(availableTimesArray);
        } else {
            // Handle the case where the response doesn't have the expected property
            console.error("Error: Unexpected response format");
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
}
