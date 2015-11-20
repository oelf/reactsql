/**
 * EventSystem
 * Global EventSystem
 */
var EventSystem = {};

/**
 * Menubar
 */
var Menubar = React.createClass({
	render: function(){
		return (
			<div id="menubar">
				<MenuItem text="File" />
				<MenuItem text="Help" />
				<br className="clear" />
			</div>
		);
	}
});

/**
 * MenuItem
 */
var MenuItem = React.createClass({
	render: function(){
		return (
			<div className="menuitem">{this.props.text}</div>
		);
	}
});

/**
 * Toolbar
 */
var Toolbar = React.createClass({
	getInitialState: function(){
		return {offset: 0};
	},
	render: function(){
		return (
			<div id="toolbar">
				<ToolbarItem cmd="start" onClick={this.handleClick} title="start" icon="fast-backward" />
				<ToolbarItem cmd="back" onClick={this.handleClick} title="back" icon="step-backward" />
				<ToolbarItem cmd="next" onClick={this.handleClick} title="next" icon="step-forward" />
				<ToolbarItem cmd="end" onClick={this.handleClick} title="end" icon="fast-forward" />
				
				<ToolbarItem cmd="refresh" title="refresh" icon="refresh" />
					
				<ToolbarItem cmd="insert" title="insert" icon="plus-circle" />	
				<ToolbarItem cmd="delete" title="delete" icon="minus-circle" />
				<ToolbarItem cmd="save" title="save" icon="check" />
				<ToolbarItem cmd="cancel" title="cancel" icon="times" />
							
				<br className="clear" />
			</div>
		);
	},
	handleClick: function(event){
		var $elem = $(event.target);
		if(event.target.nodeName == "I") {
			$elem = $elem.parent();
		}
		
		var cmd = $elem.data("cmd");
		if(cmd == "start" || cmd == "back" || cmd == "next" || cmd == "end")
		{
			var offset = this.state.offset;
			switch(cmd)
			{
				case "start":
					offset = 0;
				break;
				case "back":
					if(offset > 0)
					{
						offset--;
					}
				break;
				case "next":
					offset++;
				break;
			}
			this.setState({offset: offset});
			$(EventSystem).trigger('setTable', ['localhost', 'azwick_de', 'test1', this.state.offset]);
		}
	}
});

/**
 * ToolbarItem
 */
var ToolbarItem = React.createClass({
	render: function(){
		return (
			<div data-cmd={this.props.cmd} onClick={this.props.onClick} title={this.props.title} className="toolbaritem">
				<Icon name={this.props.icon} />
			</div>
		);
	}
});

/**
 * Icon
 * Font-Awesome
 */
var Icon = React.createClass({
	render: function(){
		var classname = "fa fa-fw fa-" + this.props.name; 
		return (
			<i className={classname}></i>
		);
	}
});

/**
 * Sidebar
 */
var Sidebar = React.createClass({
	getInitialState: function(){
		return {arrServer: []};
	},
	componentDidMount: function(){
		this.getServer();
	},
	render: function(){
		return (
			<div id="sidebar">
				<div>{this.state.arrServer}</div>
			</div>
		);
	},
	getServer: function(){
		$.post('index.php?r=main/getServer', function(postdata){
			var _arrServer = new Array();
			
			if(postdata.sql)
			{
				$(EventSystem).trigger('addSql', [postdata.sql]);
			}
			
			postdata.data.forEach(function(elem){
				var server = <Server name={elem} />;
				_arrServer.push(server);
			});
			this.setState({arrServer: _arrServer});
		}.bind(this), "json");
	}
});

/**
 * Server
 * Server element in Sidebar
 */
var Server = React.createClass({
	getDefaultProps: function(){
		return {level: 1}
	},
	getInitialState: function(){
		return {arrDatabases: []};
	},
	componentDidMount: function(){
		this.getDatabases();
	},
	render: function(){
		return (
			<div>
				<div><Icon name="server" /> {this.props.name}</div>
				<div data-level="1">{this.state.arrDatabases}</div>
			</div>
		);
	},
	getDatabases: function(){
		$.post('index.php?r=main/getDatabases/'+this.props.name, function(postdata){
			var _arrDatabases = new Array();
			
			if(postdata.sql)
			{
				$(EventSystem).trigger('addSql', [postdata.sql]);
			}
				
			postdata.data.forEach(function(elem){
				var database = <Database server={this.props.name} name={elem} />;
				_arrDatabases.push(database);
			}.bind(this));
			this.setState({arrDatabases: _arrDatabases});
		}.bind(this), "json");
	}
});
		
/**
 * Database
 * Database element in Sidebar
 */
var Database = React.createClass({
	getDefaultProps: function(){
		return {level: 2}
	},
	getInitialState: function(){
		return {arrTables: []};
	},
	componentDidMount: function(){
		this.getTables();
	},
	render: function(){
		return (
			<div>
				<div><Icon name="database" /> {this.props.name}</div>
				<div data-level="2">{this.state.arrTables}</div>
			</div>
		);
	},
	getTables: function(){
		$.post('index.php?r=main/getTables/'+this.props.name, function(postdata){
			var _arrTables = new Array();
			
			if(postdata.sql)
			{
				$(EventSystem).trigger('addSql', [postdata.sql]);
			}
			
			postdata.data.forEach(function(elem){
				var table = <Table server={this.props.server} database={this.props.name} name={elem} />;
				_arrTables.push(table);
			}.bind(this));
			this.setState({arrTables: _arrTables});
		}.bind(this), "json");
	}
});

/**
 * Table
 * Table element in Sidebar
 */
var Table = React.createClass({
	render: function(){
		return (
			<div onClick={this.select}><Icon name="table" /> {this.props.name}</div>
		);
	},
	select: function(event){
		var $elem = $(event.target);
		if(event.target.nodeName == "SPAN") {
			$elem = $elem.parent();
		}
		
		$('div.selected').removeClass('selected');
		$elem.toggleClass("selected");
		
		$(EventSystem).trigger('setTable', [this.props.server, this.props.database, this.props.name]);
	}
});

/**
 * Content
 * Contains DataTable
 */
var Content = React.createClass({
	getInitialState: function(){
		return {table: null};
	},
	render: function(){
		return (
			<div id="content">{this.state.table}</div>
		);
	},
	componentDidMount: function(){
		$(EventSystem).on('setTable', function(event, server, database, table, offset){
			if(typeof offset === "undefined")
			{
				offset = 0;
			}
			var _table = <DataTable server={server} database={database} table={table} offset={offset} />
			this.setState({table: _table});
		}.bind(this));
	}
});

/**
 * DataTable
 * SQL-Data View
 */
var DataTable = React.createClass({
	getInitialState: function(){
		return {arrRows: []};
	},
	componentDidMount: function(){
		this.getData(this.props.server, this.props.database, this.props.table, this.props.offset);
	},
	componentWillReceiveProps: function(nextprop){
		this.getData(nextprop.server, nextprop.database, nextprop.table, nextprop.offset);
	},
	render: function(){
		return (
			<table id="datatable">
				{this.state.arrRows}
			</table>
		);
	},
	getData: function(server, database, table, offset){
		$.post('index.php?r=main/getData/'+server+'/'+database+'/'+table+'/'+offset, function(postdata){
			var _arrRows = new Array();
			
			if(postdata.sql)
			{
				$(EventSystem).trigger('addSql', [postdata.sql]);
			}
			
			var _arrRows = new Array();
			postdata.data.forEach(function(arrTr){
				var arrTds = new Array();
				arrTr.forEach(function(data){
					var td = <DataTableData>{data}</DataTableData>;
					arrTds.push(td);
				});
				var tr = <DataTableRow>{arrTds}</DataTableRow>
				_arrRows.push(tr);
			});
			this.setState({arrRows: _arrRows});
		}.bind(this), "json");
	}
});

/**
 * DataTableRow
 */
var DataTableRow = React.createClass({
	render: function(){
		return (
			<tr>{this.props.children}</tr>
		);
	}
});

/**
 * DataTableData
 */
var DataTableData = React.createClass({
	render: function(){
		return (
			<td>{this.props.children}</td>
		);
	}
});

/**
 * Log
 * SQL-Logs
 */
var Log = React.createClass({
	getInitialState: function(){
		return {arrSql: []};
	},
	render: function(){
		return (
			<div id="log">{this.state.arrSql.map(function(string){
				return (<p><span>{string}</span></p>);
			})}</div>
		);
	},
	componentDidMount: function(){
		$(EventSystem).on('addSql', function(event, sql){
			var _arrSql = this.state.arrSql;
			if(typeof sql == "object")
			{
				sql.forEach(function(sqlstring){
					_arrSql.push(sqlstring);
				});
			}
			else
			{
				_arrSql.push(sql);
			}
			
			this.setState({arrSql: _arrSql});
		}.bind(this));
	}
});

/**
 * ReactSQL
 * Main class
 */
var ReactSQL = React.createClass({
	render: function(){
		return (
			<div id="main">
				<Menubar />
				<Toolbar />
				<div id="wrapper">
					<Sidebar />
					<Content />
					<br className="clear" />
				</div>
				<Log />
			</div>
		);
	}
});

ReactDOM.render(<ReactSQL />, document.body);