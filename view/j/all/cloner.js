var ClonerClass = function()
{
	this.Data = new Array();
	this.CurrentObject = new Object();
	this.Attributes = [ 'id', 'href', 'title', 'value' ];
	this.CustomAttributes = [];
	this.cloneNode = function( Data, Node, TargetNode, CustomAttributes )
	{
		this.Data = Data;
		var clonedNode = Node.cloneNode( true );
		if( TargetNode ) TargetNode.appendChild( clonedNode );
		var protect = this.Attributes;
		if( CustomAttributes )
		{
			for( var i=0; i<CustomAttributes.length; i++ ) this.Attributes.push( CustomAttributes[i] );
		}
		this.replaceVars( clonedNode );
		this.Attributes = protect;
		return clonedNode;
	}

	this.replaceVars = function( Node )
	{
		if( Node && !Node.innerHTML && Node.nodeValue )
		{
			var html = Node.nodeValue;
      		for( key in this.Data )
			{
				html = html.replace( '%' + key + '%', this.Data[key] );
			}
			Node.nodeValue = html;
		}

		if( Node && Node.attributes && Node.attributes.length )
		{
      		for( var i=0; i<this.Attributes.length; i++ )
			{
				if( Node.attributes[this.Attributes[i]] )
				{
					if( !Node.attributes[this.Attributes[i]].nodeValue ) continue;
					
					var html = Node.attributes[this.Attributes[i]].nodeValue;
				 
	      			for( key in this.Data )
					{
						html = html.replace( '%' + key + '%', this.Data[key] );
					}
					Node.attributes[this.Attributes[i]].nodeValue = html;
				}
			}
		}
		  
		if( Node && Node.childNodes && Node.childNodes.length )
		{
			for( var i=0; i<Node.childNodes.length; i++ )
			{
				this.replaceVars( Node.childNodes[i] );	
			}
		}
	}
}

var Cloner = new ClonerClass();
