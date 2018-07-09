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


/******************************************************************************
* THIS-FUNCTION take all of the hash values from the checked boxes in the dashboard_logic.php file under "Delete" table data. 
* create a map of the hash values and pass them to APHPFILE.php.
******************************************************************************/

function deletePrayers() {
    if (confirm('Are you sure you want to delete?')) {
        var checked_prayer_array = $("#delete-prayer-checkbox:checked").map(function(){
    return $(this).attr("data-pid");
    }).get(); // <----

    $.ajax({
        url: '../admin/delete_prayers.php',
        type: "POST",
        data: {checked_prayer_array:checked_prayer_array},
        dataType: 'json',
    })
        .done(function( json ) {
            alert(json);
        })
        .fail(function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!");
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
        })
        .always(function(xhr, status ) {
            alert( "The request is complete!" );
        });
    }    
    document.location.reload();
}