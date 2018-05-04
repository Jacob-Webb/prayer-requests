var context = document.getElementById("my_chart");

var my_chart = new Chart(context, {
    type: 'pie',
    data: {
        labels: ["Healing", "Provision", "Salvation"],
        datasets: [{
            label: ["Prayers"],
            backgroundColor: ["#3399FF","#FF795D", "#888888"],
            data: [healing_percentage, provision_percentage, salvation_percentage]
        }]
    },
    option: {
        title: {
            display: true,
            text: ['Prayer Requests']
        }
    }
});
