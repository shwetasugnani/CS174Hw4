

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<?php
	include "dataToJson.php";

	$config = require("config.php");
	$con = mysqli_connect($config['host'], $config['username'], $config['password'], "chartData");

	$chartType = $_REQUEST['arg1'];
	$hash = $_REQUEST['arg2'];





	$sql = "SELECT title, data FROM Data WHERE hashedData = '$hash'";
	$result = $GLOBALS['con']->query($sql);
	
	$row = $result->fetch_assoc();

	$title = $row['title'];
	$data = $row['data'];

    $formatted = formatData($data);

    function callbackfunc($data){
          $arg3 = $_REQUEST['arg3'];

          ?>
            <script type="text/javascript">
                
                <?php echo $arg3?>( <?php echo $formatted?>);
            </script>
            <?php
       

    }
	
	if ($chartType == "json"){

        echo '<div style="font-size:24pt; text-align: center; width: 500;">'. $title .'</div>';

        $JSONformatted = formatDataJSON($data);
        echo $JSONformatted;
        
    }
    else if ($chartType == "jsonp"){

        echo '<div style="font-size:24pt; text-align: center; width: 500;">'. $title .'</div>';
        $JSONformatted = formatDataJSON($data);
        $arg3 = $_REQUEST['arg3'];
        echo $arg3.'('.$JSONformatted.')';
    }
    else if ($chartType == "xml"){
        formatXML($data);


    }
    else {

	?>

	<p id="chart"></p>
	<script>

//chart.js
/**
 * Defines a class useful for making point and line graph charts.
 *
 * Example use:
 * graph = new Chart(some_html_element_id, 
 *     {"Jan":7, "Feb":20, "Dec":5}, {"title":"Test Chart - Month v Value"});
 * graph.draw();
 *
 * @param String chart_id id of tag that the chart will be drawn into
 * @param Object data a sequence {x_1:y_1, ... x_1,y_n} points to plot
 *    x_i's can be arbitrary labels, y_i's are assumes to be floats
 * @param Object (optional) properties override values for any of the
 *      properties listed in the property_defaults variable below
 */

function javascript_callback(data){
   alert(data);
}
function Chart(chart_id, data)
{
    var self = this;
    var p = Chart.prototype;
    var properties = (typeof arguments[2] !== 'undefined') ?
        arguments[2] : {};
    var container = document.getElementById(chart_id);
    if (!container) {
        return false;
    }
    var property_defaults = {
        'axes_color' : 'rgb(128,128,128)', // color of the x and y axes lines
        'caption' : '', // caption text appears at bottom
        'caption_style' : 'font-size: 14pt; text-align: center;',
            // CSS styles to apply to caption text
        'data_color' : 'rgb(0,0,255)', //color used to draw grah
        'height' : 500, //height of area to draw into in pixels
        'line_width' : 1, // width of line in line graph
        'x_padding' : 30, //x-distance left side of canvas tag to y-axis
        'y_padding' : 30, //y-distance bottom of canvas tag to x-axis
        'point_radius' : 3, //radius of points that are plot in point graph
        'tick_length' : 10, // length of tick marks along axes
        'ticks_y' : 5, // number of tick marks to use for the y axis
        'tick_font_size' : 10, //size of font to use when labeling ticks
        'title' : '', // title text appears at top
        'title_style' : 'font-size:24pt; text-align: center;',
            // CSS styles to apply to title text
        'type' : 'LineGraph', // currently, can be either a LineGraph or
            //PointGraph
        'width' : 500 //width of area to draw into in pixels
    };
    for (var property_key in property_defaults) {
        if (typeof properties[property_key] !== 'undefined') {
            this[property_key] = properties[property_key];
        } else {
            this[property_key] = property_defaults[property_key];
        }
    }
    title_tag = (this.title) ? '<div style="' + this.title_style
         + 'width:' + this.width + '" >' + this.title + '</div>' : '';
    caption_tag = (this.caption) ? '<figcaption style="' + this.caption_style
         + 'width:' + this.width + '" >' + this.caption + '</figcaption>' : '';
    container.innerHTML = '<figure>'+ title_tag + '<canvas id="' + chart_id +
        '-content" ></canvas>' + caption_tag + '</figure>';
    canvas = document.getElementById(chart_id + '-content');
    if (!canvas || typeof canvas.getContext === 'undefined') {
        return
    }
    var context = canvas.getContext("2d");
    canvas.width = this.width;
    canvas.height = this.height;
    this.data = data;
    /**
     * Main function used to draw the graph type selected
     */
    p.draw = function()
    {
        self['draw' + self.type]();
    }
    /**
     * Used to store in fields the min and max y values as well as the start
     * and end x keys, and the range = max_y - min_y
     */
    p.initMinMaxRange = function()
    {
        self.min_value = null;
        self.max_value = null;
        self.start;
        self.end;
        var key;
        for (key in data) {
           
            if (self.min_value === null) { 

                self.min_value = Math.min.apply( Math, data[key] );
                self.max_value = Math.max.apply( Math, data[key] );

                
                self.start = key;
            }
            if (Math.min.apply( Math, data[key] ) < self.min_value) {
                self.min_value = Math.min.apply( Math, data[key]);
            }
            if (Math.max.apply( Math, data[key])> self.max_value) {
                self.max_value = Math.max.apply( Math, data[key]);
            }
        }
                console.log(self.min_value);
                console.log(self.max_value);
        self.end = key;
        self.range = self.max_value - self.min_value;
    }
    /**
     * Used to draw a point at location x,y in the canvas
     */
    p.plotPoint = function(x,y)
    {
        var c = context;
        c.beginPath();
        c.arc(x, y, self.point_radius, 0, 2 * Math.PI, true);
        c.fill();
    }
    /**
     * Draws the x and y axes for the chart as well as ticks marks and values
     */
    p.renderAxes = function()
    {
        var c = context;
        var height = self.height - self.y_padding;
        c.strokeStyle = self.axes_color;
        c.lineWidth = self.line_width;
        c.beginPath();
        c.moveTo(self.x_padding - self.tick_length,
            self.height - self.y_padding);
        c.lineTo(self.width - self.x_padding,  height);  // x axis
        c.stroke();
        c.beginPath();
        c.moveTo(self.x_padding, self.tick_length);
        c.lineTo(self.x_padding, self.height - self.y_padding +
            self.tick_length);  // y axis
        c.stroke();
        var spacing_y = self.range/self.ticks_y;
        height -= self.tick_length;
        var min_y = parseFloat(self.min_value);
        var max_y = parseFloat(self.max_value);
        var num_format = new Intl.NumberFormat("en-US",
            {"maximumFractionDigits":2});
        // Draw y ticks and values
        for (var val = min_y; val < max_y + spacing_y; val += spacing_y) {
            y = self.tick_length + height * 
                (1 - (val - self.min_value)/self.range);
            c.font = self.tick_font_size + "px serif";
            c.fillText(num_format.format(val), 0, y + self.tick_font_size/2,
                self.x_padding - self.tick_length);
            c.beginPath();
            c.moveTo(self.x_padding - self.tick_length, y);
            c.lineTo(self.x_padding, y);
            c.stroke();
        }
        // Draw x ticks and values
        var dx = (self.width - 2 * self.x_padding) /
            (Object.keys(data).length - 1);
        var x = self.x_padding;
        for (key in data) {
            c.font = self.tick_font_size + "px serif";
            c.fillText(key, x - self.tick_font_size/2 * (key.length - 0.5), 
                self.height - self.y_padding +  self.tick_length +
                self.tick_font_size, self.tick_font_size * (key.length - 0.5));
            c.beginPath();
            c.moveTo(x, self.height - self.y_padding + self.tick_length);
            c.lineTo(x, self.height - self.y_padding);
            c.stroke();
            x += dx;
        }
    }
    /**
     * Draws a chart consisting of just x-y plots of points in data.
     */
    p.drawPointGraph = function()
    {
        self.initMinMaxRange();
        self.renderAxes();
        var dx = (self.width - 2*self.x_padding) /
            (Object.keys(data).length - 1);
        var c = context;
        c.lineWidth = self.line_width;
        c.strokeStyle = self.data_color;
        c.fillStyle = self.data_color;
        var height = self.height - self.y_padding - self.tick_length;
        var x = self.x_padding;
        for (key in data) {
            var array=data[key];
            var loop = data[key].length;
                while(loop>0){
                        y = self.tick_length + height *
                        (1 - (array[loop-1] - self.min_value)/self.range);
                        self.plotPoint(x, y);
                        
                loop--;
            }
             x += dx;
        }
    }
    /**
     * Draws a chart consisting of x-y plots of points in data, each adjacent
     * point pairs connected by a line segment
     */

    p.drawLineGraph = function()
    {
        self.drawPointGraph();

        var dataArray=[];
        var i=0;
        var pointValue;

        for(key in data){

            dataArray[i++]=data[key];
        }

        var rowlength=dataArray.length;
        console.log(dataArray.length);

        var collength=dataArray[0].length;
        console.log(dataArray[0].length);

        var j=0;

        while (j<collength){

            var c = context;
            c.beginPath();
            var x = self.x_padding;
            var dx =  (self.width - 2*self.x_padding) /
            (Object.keys(data).length - 1);
            var height = self.height - self.y_padding  - self.tick_length;
            c.moveTo(x, self.tick_length + height * (1 -
                    (dataArray[0][j] - self.min_value)/self.range));

            for(var i=0;i<rowlength-1;i++){
                    y = self.tick_length + height * 
                    (1 - (dataArray[i+1][j]- self.min_value)/self.range);
                    x += dx; 
                    c.lineTo(x,y);
            }
            j++;
             c.stroke();
        }
        
    }

    p.drawHistogram = function(){
            self.initMinMaxRange();
            self.renderAxes();

            var c = context;
            var height = self.height - self.y_padding;
            c.strokeStyle = self.axes_color;
            c.lineWidth = self.line_width;
            c.beginPath();
            c.fillStyle = self.data_color;
            var dx = (self.width - 2*self.x_padding) /
            (Object.keys(data).length - 1);

            var x = self.x_padding;
            console.log("x padding: ",x);
            console.log("self.tick_length", self.tick_length);
            console.log("height", self.height);
            console.log("self.min_value: ",self.min_value);
            console.log("self.range",self.range);

            for (key in data) {
            var array=data[key];
            var loop = data[key].length;
           // var dis=x-((loop*10)/2);
           var dis=x;
               for(var i=0;i<loop;i++){
                        y = self.tick_length + height *
                        (1 - (array[i] - self.min_value)/self.range);
                        console.log("array[loop]", array[i]);

                        if(array[i]==self.min_value){
                            c.fillRect(dis,y-10,2,(self.height - self.y_padding +
            self.tick_length)-y-self.tick_length)
                        }
                        else{
                            c.fillRect(dis,y,2,(self.height - self.y_padding +
            self.tick_length)-y-self.tick_length);
                        }
                        
                        dis=dis+10; 
           
            }
             x += dx;
        }

    }


}

 graph = new Chart('chart', 
    <?php echo $formatted ?>, {"title": <?php echo '"'. $title . '"' ?>,"type":<?php echo '"'. $chartType . '"' ?>});
  graph.draw();

</script>
	
	<?php
    }

    echo '<p> As a LineGraph: <a href='.$config['url'] .'/?c=chart&a=show&arg1=LineGraph&arg2='.$hash.'>'.$config['url'] .'/?c=chart&a=show&arg1=LineGraph&arg2='.$hash.'</a><p>';

    echo '<p> As a PointGraph: <a href='.$config['url'] .'/?c=chart&a=show&arg1=PointGraph&arg2='.$hash.'>'.$config['url'] .'/?c=chart&a=show&arg1=PointGraph&arg2='.$hash.'</a><p>';

    echo '<p> As a Histogram: <a href='.$config['url'] .'/?c=chart&a=show&arg1=Histogram&arg2='.$hash.'>'.$config['url'] .'/?c=chart&a=show&arg1=Histogram&arg2='.$hash.'</a><p>';

    echo '<p> As XML data: <a href='.$config['url'] .'/?c=chart&a=show&arg1=xml&arg2='.$hash.'>'.$config['url'] .'/?c=chart&a=show&arg1=xml&arg2='.$hash.'</a><p>';

    echo '<p> As JSON data: <a href='.$config['url'] .'/?c=chart&a=show&arg1=json&arg2='.$hash.'>'.$config['url'] .'/?c=chart&a=show&arg1=json&arg2='.$hash.'</a><p>';

    echo '<p> As JSONP data: <a href='.$config['url'] .'/?c=chart&a=show&arg1=jsonp&arg2='.$hash.'&arg3=javascript_callback>'.$config['url'] .'/?c=chart&a=show&arg1=jsonp&arg2='.$hash.'&arg3=javascript_callback</a><p>';


    ?>



</body>
</html>