var args = require("./pre")(process.argv);
var rdcrypto = require("./../rdcrypto")(args[0]);
console.log(rdcrypto.encrypt(args[1]));