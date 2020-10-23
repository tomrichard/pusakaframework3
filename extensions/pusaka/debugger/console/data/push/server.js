var app 		= require('express')();
var http 		= require('http').createServer(app);
var io 			= require('socket.io')(http);

var argv 		= process.argv;

var root 		= argv[2];

const watcher 	= require('chokidar');

watcher.watch(root + 'app/hmvc/').on('all', (event, path) => {
	
	console.log(event, path);

	io.emit('reload', 'true');

});

const PORT 		= process.env.PORT || 3080;

app.get('/', (req, res) => {
	res.sendFile(__dirname + '/app/views/index.html');
});

io.on('connection', (socket) => {
	console.log('a user connected');
});

http.listen(PORT, () => {
	
	// include node fs module
	var fs = require('fs');
	 
	// writeFile function with filename, content and callback function
	fs.writeFile(__dirname + '/port', PORT.toString(), function (err) {
		if (err) throw err;
		console.log('port saved.');
	});

	console.log('listening on *: ' + PORT);

});