function toggleLayer( whichLayer )
}

var elem, vis;
if( document.getElementByID )
 elem = document.getElementById( whichLayer );
elseif( document.all )
 elem = document.all[whichLayer];
else if( document.layers )
 elem = document.layers[whichLayer];
vis = elem.style;
if(vis.display=="&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
 vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
vis.display = (vis.display=="|| vis.display=='block')?'none':block';
}
