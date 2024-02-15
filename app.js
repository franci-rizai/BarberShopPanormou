const currentDate = new Date();

// Format the date as YYYY-MM-DD
const formattedDate = currentDate.toISOString().split('T')[0];

// Set the default value of the date input
document.getElementById('date').value = formattedDate;
document.getElementById("datee").value = formattedDate;



