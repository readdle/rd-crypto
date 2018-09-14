var crypto = require('crypto');

var RDCrypto = function (secret) {

    if (secret.length !== 16){
        throw new Error("Secret length should be exactly 16 characters long");
    }

    this.cryptoMarker = '-CRYPT-';
    this.cryptoMethod = 'aes-128-cbc';

    this.secret = secret;

    var md5sum = crypto.createHash('md5');
    this.iv = md5sum.update(this.secret).digest("binary").substring(0, 16);
    this.iv = Buffer.from(this.iv, "binary");
};

RDCrypto.prototype.encrypt = function (value)
{
    var cipher = crypto.createCipheriv(this.cryptoMethod, this.secret, this.iv);
    var encrypted = cipher.update(value, 'utf8', 'binary') + cipher.final('binary');
    var hexVal = Buffer.from(encrypted, 'binary');
    var newEncrypted = hexVal.toString('hex');

    return this.cryptoMarker + newEncrypted;
};

RDCrypto.prototype.decrypt = function (value)
{

    if (0 !== value.indexOf(this.cryptoMarker)) {
        return value;
    }

    var originalValue = value;
    value = value.substr(this.cryptoMarker.length);

    var decipher = crypto.createDecipheriv(this.cryptoMethod, this.secret, this.iv);
    var decrypted = decipher.update(value, 'hex', 'binary');
    var final = '';

    try {
        final = decipher.final('binary');
    } catch (err) {
        decrypted = originalValue;
    }

    return decrypted + final;
};


module.exports = function (secret) {
    return new RDCrypto(secret);
};
