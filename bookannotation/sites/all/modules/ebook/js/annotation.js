var annotation_block_div = null;

function displayAnnot(book_id,page_id)
{
    jQuery.ajax({
           url  : Drupal.settings.basePath + 'ajax/annotation_page',
           type : 'POST',
           dataType : 'json',
           data: { 'bid':book_id,'pid':page_id},
           success: function(json){
                if((json)&&(json.html)&&(json.annotation)){
                    jQuery('#block-annotation-block-annotation-page #annotation-list').replaceWith(json.html);
                var pid=page_id;
                var annotCanvas=document.getElementById('annotCanvas'+pid);
                var canvasWidth=annotCanvas.width;
                var canvasHeight=annotCanvas.height;
                var context = annotCanvas.getContext('2d');
                context.clearRect(0,0,canvasWidth,canvasHeight);
                    var annotations=json.annotation,i;
                    for (i in annotations){
                        showPosition(annotations[i]);
                    }
                }else{
                    jQuery('#block-annotation-block-annotation-page #annotation-list').text(Drupal.t("No Annotation on This page"));
                }
           },
           error: function(jqXHR, textStatus, errorThrown){
           console.log(
                       "The following error occured: "+
                       textStatus, errorThrown
                       );
           }
           });
}
function drawPencil()
{
    var pid=PDFView.page;
    document.getElementById('annotLayer'+pid).style.zIndex="9999"
    var annotCanvas=document.getElementById('annotCanvas'+pid);
    var canvasWidth=annotCanvas.width;
    var canvasHeight=annotCanvas.height;
    var ctx = annotCanvas.getContext('2d');
    //Draw Pencil
    var color="blue";
    var started=false;
    
    var pencil=[];//The Pencil Object
    var newpoly=[];//Every Stroke is treated as a Continous Polyline
    annotCanvas.addEventListener('mousedown',function(e){
                                 console.log(e);
                                 newpoly=[];//Clear the Stroke
                                 started=true;
                                 newpoly.push( {"x":e.offsetX,"y":e.offsetY});//The percentage will be saved
                                 ctx.globalAlpha = 1;
                                 ctx.beginPath();
                                 ctx.moveTo(e.offsetX, e.offsetY);
                                 ctx.strokeStyle = color;
                                 ctx.stroke();
                                 },true);
   annotCanvas.addEventListener('mousemove',function(e){
                        if(started)
                        {
                        newpoly.push( {"x":e.offsetX,"y":e.offsetY});
                        ctx.lineTo(e.offsetX,e.offsetY);
                        ctx.stroke();
                        }
                        },true);
   annotCanvas.addEventListener('mouseup',function(e){
                        started=false;
                        pencil.push(newpoly);//Push the Stroke to the Pencil Object
                        newpoly=[];//Clear the Stroke
                        var x,y,w,h;
                        x=pencil[0][0].x;
                        y=pencil[0][0].y;
                        var maxdistance=0;//The Most Remote Point to Determine the Markup Size
                        var points="";
                        for (var i=0;i<pencil.length;i++)
                        {
                        newpoly=pencil[i];
                        for(j=0;j<newpoly.length;j++)
                        {
                        points+=newpoly[j].x/canvasWidth+','+newpoly[j].y/canvasHeight+' ';
                        if ((newpoly[j].x+newpoly[j].y)>maxdistance)
                        {
                        maxdistance=newpoly[j].x+newpoly[j].y;
                        w=Math.abs(newpoly[j].x-x)/canvasWidth;
                        h=Math.abs(newpoly[j].y-y)/canvasHeight;
                        }
                        }
                        points=points.slice(0, -1)
                        points+=';';
                        }
                            if(document.getElementById('selectedText'))
                            {
                                document.getElementById('page_id').value=pid;
                                document.getElementById('startx').value=x/canvasWidth;
                                document.getElementById('starty').value=y/canvasHeight;
                                document.getElementById('width').value=(e.offsetX-x)/canvasWidth;
                                document.getElementById('height').value=(e.offsetY-y)/canvasHeight;
                                document.getElementById('type').value=1;
                                document.getElementById('points').value=points;
                                document.getElementById('selectedText').value="";
                                document.getElementById('selectedText').disabled=false;
                                document.getElementById('selectedText').placeholder="Please add some texts associating with the pencil annotation";
                            }
                        },true);
}
function showPosition(annot)
{
    
    var startx=parseFloat(annot.startx);
    var starty=parseFloat(annot.starty);
    var width=parseFloat(annot.width);
    var height=parseFloat(annot.height);
    var pid=PDFView.page;
    var annotCanvas=document.getElementById('annotCanvas'+pid);
    var canvasWidth=annotCanvas.width;
    var canvasHeight=annotCanvas.height;
    var context = annotCanvas.getContext('2d');
    switch(annot.type)
    {
        case "0":
            // Stroke Text
            context.globalAlpha=1;
            context.fillStyle='#00F';
            context.font="Bold 20px Sans-Serif";
            context.fillText(annot.aid, startx*canvasWidth,starty*canvasHeight);
            context.globalAlpha=0.2;
            context.beginPath();
            context.arc(startx*canvasWidth+8, starty*canvasHeight-5, 15, 0 , 2 * Math.PI, false);
            context.fillStyle = "blue";
            context.fill();
            break;
        case "1":
            var points=annot.points;
            var poly=points.split(';');
            var point,px,py;
            context.beginPath();
            for (var k=0;k<poly.length;k++)
            {
                var p=poly[k].split(' ');
                for (var j=0;j<p.length;j++)
                {
                    point=p[j].split(',');
                    px=point[0]*canvasWidth;
                    py=point[1]*canvasHeight;
                    if(j==0) context.moveTo(px, py);
                    else context.lineTo(px, py);
                    context.strokeStyle = "blue";
                    context.stroke();
                }
            }
            break;
            
    }
}
