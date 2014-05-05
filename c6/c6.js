#!/usr/bin/env node

if ((process.version.split('.')[1]|0) < 10) {
	console.log('Please, upgrade your node version to 0.10+');
	process.exit();
}

var net = require('net');
var util = require('util');
var crypto = require('crypto');

var readline = require('readline');

var dhs, dhc, secrets, secretc, state = 0, key;
var rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

rl.on('line', function(line){
    key=line;
})

var options = {
	'port': 6969,
	'host': '54.83.207.90',
}

var socket = net.connect(options, function() {
	//console.log("Conectado!");
});

socket.on('data', function(data) {

	recv = data.toString().trim().split(':');
	//console.log(recv[0]+" "+recv[1]);
	data = recv[1].split('|');

	if (recv[0]=="CLIENT->SERVER" && data[0] == 'key') {
		//Imitando al servidor
		dhc = crypto.createDiffieHellman(data[1], 'hex');
		dhc.generateKeys();
		secretc = dhc.computeSecret(data[2], 'hex');
		//socket.write(util.format('key|%s\n', dhc.getPublicKey('hex')));
		
		//Impersonando al cliente
		dhs = crypto.createDiffieHellman(256);
		dhs.generateKeys();
		socket.write(util.format('key|%s|%s\n', dhs.getPrime('hex'), dhs.getPublicKey('hex')));
		
	} else if (recv[0]=="SERVER->CLIENT" && data[0] == 'key') {
		//Impersonando al cliente
		secrets = dhs.computeSecret(data[1], 'hex');
		
		//Imitando al servidor
		socket.write(util.format('key|%s\n', dhc.getPublicKey('hex')));
		
	} else if (recv[0]=="CLIENT->SERVER" && data[0] == 'keyphrase') {
		//Imitando al servidor
		var decipher = crypto.createDecipheriv('aes-256-ecb', secretc, '');
		var keyphrase = decipher.update(data[1], 'hex', 'utf8') + decipher.final('utf8');
		//key=keyphrase;
		//console.log("KEYPHRASE: "+keyphrase);
		
		//Impersonando al cliente
		var cipher = crypto.createCipheriv('aes-256-ecb', secrets, '');
		var keyphrase = cipher.update(key, 'utf8', 'hex') + cipher.final('hex');
		socket.write(util.format('keyphrase|%s\n', keyphrase));
	} else if (recv[0]=="SERVER->CLIENT" && data[0] == 'result') {
		//Impersonando al cliente
		var decipher = crypto.createDecipheriv('aes-256-ecb', secrets, '');
		var message = decipher.update(data[1], 'hex', 'utf8') + decipher.final('utf8');
		console.log(message);
		
		//Imitando al servidor
		socket.end(); //Ya no hay servidor, ya tenemos lo que queremos lol
	} else {
		socket.write(data[0]);
	}

});