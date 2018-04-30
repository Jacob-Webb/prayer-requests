var myCanvas = document.getElementById("myCanvas");
myCanvas.width = 300;
myCanvas.height = 300;

//sets context to a 2d drawing
var ctx = myCanvas.getContext("2d");

// pie_slices holds the categories and values of the piechart
var pie_slices = {
    "Healing": healing_percentage,
    "Provision": provision_percentage,
    "Salvation": salvation_percentage
};

/*******************************************************************************
* drawPieSlice is the function responsible for drawing and filling a pie slice
* @param ctx the context that the "slice" will be drawn on
* @param {Number} centerX x-coordinate of the center of the piechart
* @param {Number} centerY y-coordinate of the center of the piechart
* @param {Number} radius the x-coordinate of the line end point
* @param {Number} startAngle the start angle in radians where the portion of the
*           circle starts
* @param {Number} endAngle the end angle in radians where the portion of the
            circle ends
*******************************************************************************/
function drawPieSlice(ctx,centerX, centerY, radius, startAngle, endAngle, color ){
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.moveTo(centerX,centerY);
    ctx.arc(centerX, centerY, radius, startAngle, endAngle);
    ctx.closePath();
    ctx.fill();
}

/*******************************************************************************
* Piechart class
* @param {Object} options contain information for canvas, context, data, and colors
*******************************************************************************/
var Piechart = function(options){
    this.options = options;
    this.canvas = options.canvas;
    this.ctx = this.canvas.getContext("2d");
    this.colors = options.colors;

    //draw the piechart
    this.draw = function(){
        var total_value = 0;
        var color_index = 0;
        //sum up all the values from data for the total value
        for (var category in this.options.data){
            var val = this.options.data[category];
            total_value += val;
        }

        var start_angle = 0;
        for (category in this.options.data){
            val = this.options.data[category];
            //s = r*theta where s = arc length, r = radius, theta = angle
            //angle (as a % of 2*pi) => value/total = x/(2*pi) => x = (2*pi*value) / total
            var slice_angle = 2 * Math.PI * val / total_value;
            //radius is lesser of (1/2)width and (1/2) height
            var pieRadius = Math.min(this.canvas.width/2,this.canvas.height/2);


            drawPieSlice(
                this.ctx,
                // center of piechart at center of width & height
                this.canvas.width/2,
                this.canvas.height/2,

                Math.min(this.canvas.width/2,this.canvas.height/2),
                start_angle,
                //end angle is begin + value % in radians
                start_angle+slice_angle,
                this.colors[color_index%this.colors.length]
            );

            //start next slice at end of last slice
            start_angle += slice_angle;
            color_index++;
        }
        //draw labels on top of chart
        start_angle = 0;
        var categories = ["Healing", "Provision", "Salvation"];
        var cat_ind = 0;
        for (category in this.options.data){
            val = this.options.data[category];
            slice_angle = 2 * Math.PI * val / total_value;
            var pieRadius = Math.min(this.canvas.width/2,this.canvas.height/2);
            //label x coordinate (center of canvas + half the radius * x portion of half way through the angle)
            var labelX = this.canvas.width/2 + (pieRadius/2) * Math.cos(start_angle + slice_angle/2);
            //label y coordinate (center of canvas + half the radius * y portion of half way through the angle)
            var labelY = this.canvas.width/2 + (pieRadius/4) * Math.sin(start_angle + slice_angle/2);
            var labelText = categories[cat_ind++];
            this.ctx.fillStyle = "white";
            this.ctx.font = "bold 15px Arial";
            this.ctx.fillText(labelText, labelX,labelY);
            start_angle += slice_angle;
        }
    }
}

var myPiechart = new Piechart(
    {
        canvas:myCanvas,
        data:pie_slices,
        colors:["#3399FF","#FF794D", "#888888"]
    }
);
myPiechart.draw();
