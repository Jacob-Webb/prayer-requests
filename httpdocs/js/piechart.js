var context = document.getElementById("my_chart");

var my_chart = new Chart(context, {
    type: 'pie',
    data: {
        labels: ["Healing", "Provision", "Salvation", "Circumstances"],
        datasets: [{
            label: ["Prayers"],
            backgroundColor: ["#3399FF","#FF795D", "#19A319", "#FFFF33"],
            data: [healing_percentage, provision_percentage, salvation_percentage, circumstance_percentage]
        }]
    },
    option: {
        title: {
            display: true,
            text: ['Prayer Requests']
        }
    }
});
