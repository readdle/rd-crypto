const crypto       = require('crypto');
const IV_LENGTH    = 16;
const CRYPT_METHOD = 'aes-128-cbc';

function RDCryptoV1(secret) {
    if (secret.length !== IV_LENGTH) {
        throw new Error("Secret length should be exactly 16 characters long");
    }

    this.cryptoMarker = '-CRYPT-';
    this.secret       = secret;

    const md5sum = crypto.createHash('md5');
    this.iv = md5sum.update(this.secret).digest('binary').substring(0, 16);
    this.iv = Buffer.from(this.iv, 'binary');
}

RDCryptoV1.prototype.encrypt = function (value) {
    const cipher       = crypto.createCipheriv(CRYPT_METHOD, this.secret, this.iv);
    const encrypted    = cipher.update(value, 'utf8', 'binary') + cipher.final('binary');
    const hexVal       = new Buffer(encrypted, 'binary');
    const newEncrypted = hexVal.toString('hex');

    return this.cryptoMarker + newEncrypted;
};

RDCryptoV1.prototype.decrypt = function (value) {
    if (0 !== value.indexOf(this.cryptoMarker)) {
        return value;
    }

    const originalValue = value;
    value = value.substr(this.cryptoMarker.length);

    try {
        const decipher  = crypto.createDecipheriv(CRYPT_METHOD, this.secret, this.iv);
        const decrypted = decipher.update(value, 'hex', 'binary');

        return `${decrypted}${decipher.final('binary')}`;
    } catch (err) {
        return originalValue;
    }
};

module.exports = function (secret) {
    return new RDCryptoV1(secret);
};
